<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Service\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);

        $unit = Unit::findOrFail($request->unit_id);

        if ($unit->status === 'sold') {
            return $this->error(__('api.unit_already_sold'), 403);
        }

        if ($unit->status === 'rented') {
            return $this->error(__('api.unit_already_rented'), 403);
        }

        if ($unit->status !== 'approved') {
            return $this->error(__('api.unit_not_approved'), 403);
        }

        try {
            $user = $request->user();
            $paymentUrl = $this->paymentService->initiatePayment($unit, $user);

            return $this->success([
                'payment_url' => $paymentUrl,
            ], __('api.payment_initiated_successfully'));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function callback(Request $request)
    {
        // Paymob callback logic
        $data = $request->all();
        $success = $this->paymentService->handleCallback($data);

        if ($success) {
            // return $this->success([], __('api.payment_successful'));
            return redirect()->to(config('app.frontend_url') . '/payment-success');
        }

        // return $this->error(__('api.payment_failed_or_canceled'), 400);
        return redirect()->to(config('app.frontend_url') . '/payment-failed');
    }
}
