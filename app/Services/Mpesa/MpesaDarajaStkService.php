<?php

namespace App\Services\Mpesa;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaDarajaStkService
{
    protected function baseUrl(): string
    {
        return config('mpesa.sandbox')
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
    }

    public function accessToken(): ?string
    {
        $key = config('mpesa.consumer_key');
        $secret = config('mpesa.consumer_secret');
        if (! $key || ! $secret) {
            return null;
        }

        $cached = Cache::get('mpesa:daraja:access_token');
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $basic = base64_encode($key.':'.$secret);
        $response = Http::timeout(25)
            ->withHeaders(['Authorization' => 'Basic '.$basic])
            ->get($this->baseUrl().'/oauth/v1/generate?grant_type=client_credentials');

        if (! $response->successful()) {
            Log::warning('mpesa.oauth_failed', ['body' => $response->body()]);

            return null;
        }

        $token = $response->json('access_token');
        if (! is_string($token) || $token === '') {
            return null;
        }

        Cache::put('mpesa:daraja:access_token', $token, now()->addSeconds(50));

        return $token;
    }

    /**
     * @return array{ok: bool, message: string, checkout_request_id?: string, merchant_request_id?: string, raw?: mixed}
     */
    public function initiateStkPush(
        string $phone254Digits,
        float $amount,
        string $accountReference,
        string $transactionDesc
    ): array {
        $token = $this->accessToken();
        if (! $token) {
            return ['ok' => false, 'message' => 'Daraja OAuth failed. Check MPESA_CONSUMER_KEY/SECRET.'];
        }

        $shortcode = (string) config('mpesa.shortcode', '');
        $passkey = (string) config('mpesa.passkey', '');
        $callback = (string) config('mpesa.stk_callback_url', '');
        if ($shortcode === '' || $passkey === '' || $callback === '') {
            return ['ok' => false, 'message' => 'MPESA_SHORTCODE, MPESA_PASSKEY, or MPESA_STK_CALLBACK_URL missing.'];
        }

        $timestamp = now()->format('YmdHis');
        $password = base64_encode($shortcode.$passkey.$timestamp);

        $phone = preg_replace('/\D/', '', $phone254Digits) ?? '';
        if (str_starts_with($phone, '0')) {
            $phone = '254'.substr($phone, 1);
        }
        if (! str_starts_with($phone, '254')) {
            $phone = '254'.$phone;
        }

        $partyB = $shortcode;
        $type = config('mpesa.transaction_type', 'CustomerPayBillOnline');

        $payload = [
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $type,
            'Amount' => (int) round($amount),
            'PartyA' => $phone,
            'PartyB' => $partyB,
            'PhoneNumber' => $phone,
            'CallBackURL' => $callback,
            'AccountReference' => substr(preg_replace('/[^A-Za-z0-9\-]/', '', $accountReference) ?? '', 0, 12),
            'TransactionDesc' => substr(preg_replace('/[^A-Za-z0-9 ]/', '', $transactionDesc) ?? '', 0, 13),
        ];

        $response = Http::timeout(30)
            ->withToken($token)
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl().'/mpesa/stkpush/v1/processrequest', $payload);

        $json = $response->json();
        if (! $response->successful()) {
            Log::warning('mpesa.stk_http_error', ['status' => $response->status(), 'body' => $response->body()]);

            return ['ok' => false, 'message' => 'STK HTTP error', 'raw' => $json];
        }

        $desc = $json['CustomerMessage'] ?? $json['ResponseDescription'] ?? '';
        $respCode = (int) ($json['ResponseCode'] ?? -1);
        if ($respCode !== 0) {
            return ['ok' => false, 'message' => (string) $desc, 'raw' => $json];
        }

        $checkoutId = $json['CheckoutRequestID'] ?? null;
        $merchantId = $json['MerchantRequestID'] ?? null;

        return [
            'ok' => true,
            'message' => (string) $desc,
            'checkout_request_id' => is_string($checkoutId) ? $checkoutId : null,
            'merchant_request_id' => is_string($merchantId) ? $merchantId : null,
            'raw' => $json,
        ];
    }
}
