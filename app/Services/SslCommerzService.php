<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class SslCommerzService
{
    private string $storeId;
    private string $storePassword;
    private bool   $isLive;

    private string $initUrl;
    private string $validateUrl;

    public function __construct()
    {
        $this->storeId       = Setting::get('sslcommerz_store_id', '');
        $this->storePassword = Setting::get('sslcommerz_store_password', '');
        $this->isLive        = Setting::get('sslcommerz_is_live', '0') === '1';

        $this->initUrl = $this->isLive
            ? 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';

        $this->validateUrl = $this->isLive
            ? 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';
    }

    /**
     * Initiate a payment session with SSLCommerz.
     * Returns ['status' => 'SUCCESS', 'GatewayPageURL' => '...'] on success.
     */
    public function initiate(array $payload): array
    {
        $payload = array_merge($payload, [
            'store_id'   => $this->storeId,
            'store_passwd' => $this->storePassword,
        ]);

        $response = Http::asForm()
            ->timeout(30)
            ->post($this->initUrl, $payload);

        if ($response->failed()) {
            return ['status' => 'FAILED', 'failedreason' => 'Could not connect to SSLCommerz.'];
        }

        return $response->json() ?? ['status' => 'FAILED', 'failedreason' => 'Invalid response from SSLCommerz.'];
    }

    /**
     * Validate a transaction with SSLCommerz using val_id.
     * Returns the full validation response array.
     */
    public function validate(string $valId): array
    {
        $response = Http::timeout(30)->get($this->validateUrl, [
            'val_id'       => $valId,
            'store_id'     => $this->storeId,
            'store_passwd' => $this->storePassword,
            'v'            => 1,
            'format'       => 'json',
        ]);

        if ($response->failed()) {
            return ['status' => 'INVALID_TRANSACTION'];
        }

        return $response->json() ?? ['status' => 'INVALID_TRANSACTION'];
    }

    public function isConfigured(): bool
    {
        return $this->storeId !== '' && $this->storePassword !== '';
    }
}
