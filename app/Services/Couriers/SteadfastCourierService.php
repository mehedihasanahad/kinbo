<?php

declare(strict_types=1);

namespace App\Services\Couriers;

use App\Contracts\CourierServiceInterface;
use App\Models\Order;
use App\Models\Setting;
final class SteadfastCourierService implements CourierServiceInterface
{
    private string $apiKey;
    private string $secretKey;

    public function __construct()
    {
        $this->apiKey    = Setting::get('steadfast_api_key', '');
        $this->secretKey = Setting::get('steadfast_secret_key', '');
    }

    public function label(): string
    {
        return 'Steadfast';
    }

    public function isConfigured(): bool
    {
        return filled($this->apiKey) && filled($this->secretKey);
    }

    public function createOrder(Order $order): array
    {
        $address = implode(', ', array_filter([
            $order->ship_address,
            $order->ship_city,
            $order->ship_district,
        ]));

        $codAmount = $order->payment_method === Order::METHOD_COD
            ? (float) $order->total_amount
            : 0;

        $response = $this->client()->placeOrder([
            'invoice'           => $order->order_number,
            'recipient_name'    => $order->ship_name,
            'recipient_phone'   => $order->ship_phone,
            'recipient_address' => $address,
            'cod_amount'        => $codAmount,
            'note'              => $order->notes ?? '',
        ]);

        return is_array($response) ? $response : (array) $response;
    }

    public function getStatusByConsignmentId(string $consignmentId): array
    {
        $response = is_array($r = $this->client()->checkDeliveryStatusByConsignmentId($consignmentId)) ? $r : (array) $r;

        if (isset($response['message']) && ! isset($response['delivery_status'])) {
            throw new \RuntimeException($response['message']);
        }

        return $response;
    }

    public function getCurrentBalance(): array
    {
        $response = is_array($r = $this->client()->getCurrentBalance()) ? $r : (array) $r;

        if (! array_key_exists('current_balance', $response)) {
            throw new \RuntimeException($response['message'] ?? 'Steadfast API returned an unexpected response. Check your credentials.');
        }

        return $response;
    }

    public function validateWebhookToken(string $token): bool
    {
        $bearer = Setting::get('steadfast_bearer_token', '');

        return filled($bearer) && hash_equals($bearer, $token);
    }

    private function client(): \SteadFast\SteadFastCourierLaravelPackage\SteadfastCourier
    {
        config([
            'steadfast-courier.api_key'    => $this->apiKey,
            'steadfast-courier.secret_key' => $this->secretKey,
        ]);

        return new \SteadFast\SteadFastCourierLaravelPackage\SteadfastCourier();
    }
}
