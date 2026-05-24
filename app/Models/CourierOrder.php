<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierOrder extends Model
{
    protected $fillable = [
        'order_id', 'courier', 'consignment_id', 'invoice',
        'tracking_code', 'status', 'cod_amount',
        'response_payload', 'error_message', 'dispatched_at',
    ];

    protected function casts(): array
    {
        return [
            'cod_amount'       => 'decimal:2',
            'response_payload' => 'array',
            'dispatched_at'    => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isDispatched(): bool
    {
        return $this->consignment_id !== null;
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public static function courierLabel(string $courier): string
    {
        return match ($courier) {
            'steadfast' => 'Steadfast',
            'pathao'    => 'Pathao',
            default     => ucfirst($courier),
        };
    }

    public static function statusColor(string $status): string
    {
        return match (strtolower($status)) {
            'delivered'                                          => 'success',
            'pending', 'in_review', 'pickup_requested', 'pickup' => 'warning',
            'on_transit', 'on_the_way'                          => 'info',
            'partial_delivered'                                  => 'info',
            'hold', 'return_requested'                          => 'gray',
            'cancelled', 'failed', 'delivery_failed', 'returned' => 'danger',
            default                                              => 'gray',
        };
    }
}
