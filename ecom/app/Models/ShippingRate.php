<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    protected $fillable = [
        'zone_id', 'method_name', 'cost', 'free_shipping_above',
        'estimated_days_min', 'estimated_days_max', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost'                => 'decimal:2',
            'free_shipping_above' => 'decimal:2',
            'is_active'           => 'boolean',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }

    public function isFreeFor(float $orderTotal): bool
    {
        return $this->free_shipping_above !== null && $orderTotal >= $this->free_shipping_above;
    }

    public function getEffectiveCostAttribute(): float
    {
        return $this->is_active ? (float) $this->cost : 0.0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
