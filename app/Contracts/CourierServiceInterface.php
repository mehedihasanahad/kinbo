<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Order;

interface CourierServiceInterface
{
    public function createOrder(Order $order): array;

    public function getStatusByConsignmentId(string $consignmentId): array;

    public function getCurrentBalance(): array;

    public function isConfigured(): bool;

    public function label(): string;
}
