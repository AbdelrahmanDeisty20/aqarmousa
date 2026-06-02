<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobService
{
    protected $apiKey;
    protected $hmac;
    protected $integrationId;
    protected $iframeId;
    protected $mode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->hmac = config('services.paymob.hmac');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
        $this->mode = config('services.paymob.mode', 'test');

        $this->baseUrl = $this->mode === 'live'
            ? 'https://accept.paymob.com'
            : 'https://accept.paymobsolutions.com';
    }

    public function getAuthToken()
    {
        $response = Http::asJson()->post("{$this->baseUrl}/api/auth/tokens", [
            'api_key' => $this->apiKey,
        ]);

        Log::info('Paymob Auth Response:', $response->json() ?? []);

        return $response->json()['token'] ?? null;
    }

    public function createOrder($token, $orderId, $amount)
    {
        $amountCents = $amount * 100;

        $response = Http::asJson()->post("{$this->baseUrl}/api/ecommerce/orders", [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $amountCents,
            'currency' => 'EGP',
            'merchant_order_id' => $orderId . '_' . time(),
            'items' => [],
        ]);

        Log::info('Paymob Create Order Response:', $response->json() ?? []);

        return $response->json()['id'] ?? null;
    }

    public function getPaymentKey($token, $paymobOrderId, $amount, $user)
    {
        $amountCents = $amount * 100;

        $billingData = [
            'apartment' => 'NA',
            'email' => $user->email ?? 'test@example.com',
            'floor' => 'NA',
            'first_name' => $user->name ?? 'User',
            'street' => 'NA',
            'building' => 'NA',
            'phone_number' => $user->phone ?? '0123456789',
            'shipping_method' => 'NA',
            'postal_code' => 'NA',
            'city' => 'Cairo',
            'country' => 'EG',
            'last_name' => $user->name ?? 'User',
            'state' => 'NA',
        ];

        $response = Http::asJson()->post("{$this->baseUrl}/api/acceptance/payment_keys", [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $paymobOrderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId,
            'lock_order_when_paid' => false,
            'redirect_url' => route('api.payment.callback'),
        ]);

        Log::info('Paymob Payment Key Response:', $response->json() ?? []);

        return $response->json()['token'] ?? null;
    }

    public function getIframeUrl($paymentKey)
    {
        return "{$this->baseUrl}/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
    }
}
