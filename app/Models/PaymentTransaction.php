<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    const GATEWAY_SSLCOMMERZ = 'sslcommerz';
    const GATEWAY_BKASH      = 'bkash';
    const GATEWAY_NAGAD      = 'nagad';
    const GATEWAY_COD        = 'cod';

    const STATUS_PENDING   = 'pending';
    const STATUS_SUCCESS   = 'success';
    const STATUS_FAILED    = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED  = 'refunded';

    protected $fillable = [
        'order_id', 'gateway', 'tran_id', 'val_id', 'bank_tran_id',
        'amount', 'currency', 'card_type', 'status', 'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'raw_response' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}
