<?php

declare(strict_types=1);

namespace App\Services\Couriers;

use App\Contracts\CourierServiceInterface;
use App\Models\Order;
use App\Models\PathaoDistrictMapping;
use App\Models\Setting;

final class PathaoService implements CourierServiceInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $username;
    private string $password;
    private bool   $sandbox;

    public function __construct()
    {
        $this->clientId     = Setting::get('pathao_client_id', '');
        $this->clientSecret = Setting::get('pathao_client_secret', '');
        $this->username     = Setting::get('pathao_username', '');
        $this->password     = Setting::get('pathao_password', '');
        $this->sandbox      = (bool) Setting::get('pathao_sandbox', '1');
    }

    public function label(): string
    {
        return 'Pathao';
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId)
            && filled($this->clientSecret)
            && filled($this->username)
            && filled($this->password);
    }

    public function createOrder(Order $order): array
    {
        $mapping = PathaoDistrictMapping::findByDistrict($order->ship_district ?? '');

        if ($mapping === null) {
            return [
                'consignment' => null,
                'message'     => 'No Pathao district mapping for "' . ($order->ship_district ?? '') . '". Configure it in Courier → Pathao District Mappings.',
            ];
        }

        $codAmount = $order->payment_method === Order::METHOD_COD
            ? (float) $order->total_amount
            : 0.0;

        $payload = [
            'store_id'            => (int) Setting::get('pathao_store_id', 0),
            'merchant_order_id'   => $order->order_number,
            'recipient_name'      => $order->ship_name,
            'recipient_phone'     => $order->ship_phone,
            'recipient_address'   => implode(', ', array_filter([
                $order->ship_address,
                $order->ship_city,
                $order->ship_district,
            ])),
            'recipient_city'      => $mapping->pathao_city_id,
            'recipient_zone'      => $mapping->pathao_zone_id,
            'delivery_type'       => (int) Setting::get('pathao_delivery_type', 48),
            'item_type'           => 2,
            'item_quantity'       => 1,
            'item_weight'         => (float) Setting::get('pathao_item_weight', 0.5),
            'amount_to_collect'   => $codAmount,
            'special_instruction' => $order->notes ?? '',
        ];

        if ($mapping->pathao_area_id !== null) {
            $payload['recipient_area'] = $mapping->pathao_area_id;
        }

        $raw = $this->client()->createOrder($payload);

        $data = $raw['data'] ?? [];

        if (empty($data['consignment_id'])) {
            return [
                'message'     => $raw['message'] ?? 'Pathao returned no consignment ID.',
                'consignment' => null,
            ];
        }

        return [
            'consignment' => [
                'consignment_id' => (string) $data['consignment_id'],
                'tracking_code'  => (string) $data['consignment_id'],
                'status'         => strtolower((string) ($data['order_status'] ?? 'pending')),
            ],
        ];
    }

    public function getStatusByConsignmentId(string $consignmentId): array
    {
        $raw  = $this->client()->orderInfo($consignmentId);
        $data = $raw['data'] ?? [];

        if (empty($data)) {
            throw new \RuntimeException($raw['message'] ?? 'No data returned from Pathao.');
        }

        return [
            'delivery_status' => strtolower((string) ($data['order_status'] ?? 'unknown')),
            'consignment_id'  => $consignmentId,
        ];
    }

    public function getCurrentBalance(): array
    {
        throw new \RuntimeException('Balance check is not supported by Pathao Courier.');
    }

    public function fetchStores(): array
    {
        return $this->client()->storeInfo();
    }

    public function fetchCities(): array
    {
        return $this->client()->cities();
    }

    public function fetchZones(int $cityId): array
    {
        return $this->client()->zones($cityId);
    }

    private function client(): \Nur\Pathao\Services\PathaoService
    {
        config([
            'pathao.client_id'     => $this->clientId,
            'pathao.client_secret' => $this->clientSecret,
            'pathao.username'      => $this->username,
            'pathao.password'      => $this->password,
            'pathao.sandbox'       => $this->sandbox,
        ]);

        // Clear cached token so new credentials take effect immediately after save
        // (token is re-issued on first API call and cached for 2 h)
        return new \Nur\Pathao\Services\PathaoService();
    }
}
