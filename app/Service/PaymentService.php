<?php

namespace App\Service;

use App\Models\Transaction;
use App\Models\Unit;
use App\Services\PaymobService;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    public function initiatePayment(Unit $unit, $user)
    {
        // 1. Get Auth Token
        $authToken = $this->paymob->getAuthToken();
        if (!$authToken) {
            Log::error('Paymob Auth Failed for unit ID: ' . $unit->id);
            throw new \Exception('Paymob Authentication Failed');
        }

        // 2. Create Order
        $paymobOrderId = $this->paymob->createOrder($authToken, $unit->id, $unit->price);
        if (!$paymobOrderId) {
            Log::error('Paymob Order Failed for unit ID: ' . $unit->id);
            throw new \Exception('Paymob Order Registration Failed');
        }

        // 3. Get Payment Key
        $paymentKey = $this->paymob->getPaymentKey($authToken, $paymobOrderId, $unit->price, $user);
        if (!$paymentKey) {
            Log::error('Paymob Payment Key Failed for unit ID: ' . $unit->id);
            throw new \Exception('Paymob Payment Key Generation Failed');
        }

        // Reserve the unit immediately to prevent double booking
        if ($unit->offer_type === 'sale') {
            $unit->update([
                'status' => 'sold',
                'sold_at' => now(),
            ]);
        } elseif ($unit->offer_type === 'rent') {
            $unit->update([
                'status' => 'rented',
                'rented_at' => now(),
            ]);
        }

        // Create initial transaction record
        Transaction::create([
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'amount' => $unit->price,
            'payment_status' => 'pending',
            'transaction_ref' => $paymobOrderId,
        ]);

        return $this->paymob->getIframeUrl($paymentKey);
    }

    public function handleCallback($data)
    {
        // Paymob success can be boolean or string 'true'
        $success = false;
        if (isset($data['success'])) {
            $success = ($data['success'] === true || $data['success'] === 'true');
        }

        $transactionRef = $data['order'] ?? null;

        if (!$transactionRef) {
            return false;
        }

        $transaction = Transaction::where('transaction_ref', $transactionRef)->first();

        if (!$transaction) {
            Log::error('Paymob Callback: Transaction not found for ref: ' . $transactionRef);
            return false;
        }

        if ($success) {
            $transaction->update([
                'payment_status' => 'paid',
            ]);

            // Notify Admins via Filament (Synchronous)
            try {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notifyNow(new \App\Notifications\SuccessfulTransactionNotification($transaction));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Transaction Notification failed: ' . $e->getMessage());
            }
        } else {
            $transaction->update([
                'payment_status' => 'failed',
            ]);
        }

        $unit = $transaction->unit;
        if ($unit) {
            if ($success) {
                $status = ($unit->offer_type === 'rent' ? 'rented' : 'sold');
                $timestampField = ($unit->offer_type === 'rent' ? 'rented_at' : 'sold_at');

                if ($unit->status !== $status) {
                    $unit->update([
                        'status' => $status,
                        $timestampField => now(),
                    ]);
                }
            } else {
                // Payment failed or was canceled: revert unit to 'approved'
                $unit->update([
                    'status' => 'approved',
                    'sold_at' => null,
                    'rented_at' => null,
                ]);
            }
        }

        return $success;
    }
}
