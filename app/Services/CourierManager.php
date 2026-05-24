<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\CourierServiceInterface;
use App\Services\Couriers\PathaoService;
use App\Services\Couriers\SteadfastCourierService;
use InvalidArgumentException;

final class CourierManager
{
    /** Returns all courier slugs and their human-readable labels. */
    public function available(): array
    {
        return [
            'steadfast' => 'Steadfast',
            'pathao'    => 'Pathao',
        ];
    }

    /** Resolve a courier service by its slug. */
    public function driver(string $courier): CourierServiceInterface
    {
        return match ($courier) {
            'steadfast' => app(SteadfastCourierService::class),
            'pathao'    => app(PathaoService::class),
            default     => throw new InvalidArgumentException("Unsupported courier provider: [{$courier}]"),
        };
    }
}
