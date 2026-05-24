<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\CourierOrder;
use App\Models\Order;
use App\Services\CourierManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class DispatchCourierOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;
    public int $timeout = 60;

    public function __construct(
        public readonly int    $orderId,
        public readonly string $courier,
    ) {}

    public function handle(CourierManager $manager): void
    {
        $order = Order::with('courierOrder')->findOrFail($this->orderId);

        // Idempotency: skip if already successfully dispatched
        if ($order->courierOrder && $order->courierOrder->isDispatched()) {
            return;
        }

        $codAmount = $order->payment_method === Order::METHOD_COD
            ? (float) $order->total_amount
            : 0.0;

        // Create (or reuse a failed) CourierOrder record
        $courierOrder = CourierOrder::updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier'    => $this->courier,
                'invoice'    => $order->order_number,
                'cod_amount' => $codAmount,
                'status'     => 'pending',
                'error_message' => null,
            ],
        );

        try {
            $service  = $manager->driver($this->courier);
            $response = $service->createOrder($order);

            $consignment = $response['consignment'] ?? null;

            if ($consignment && isset($consignment['consignment_id'])) {
                $courierOrder->update([
                    'consignment_id'  => (string) $consignment['consignment_id'],
                    'tracking_code'   => $consignment['tracking_code'] ?? null,
                    'status'          => $consignment['status'] ?? 'pending',
                    'response_payload' => $response,
                    'error_message'   => null,
                    'dispatched_at'   => now(),
                ]);

                // Mirror tracking code onto the order
                $order->update(['tracking_number' => $consignment['tracking_code'] ?? null]);
            } else {
                $courierOrder->update([
                    'status'          => 'failed',
                    'response_payload' => $response,
                    'error_message'   => $response['message'] ?? 'Unexpected API response.',
                ]);
            }
        } catch (\Throwable $e) {
            $courierOrder->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('Courier dispatch failed after all retries', [
            'order_id' => $this->orderId,
            'courier'  => $this->courier,
            'error'    => $e->getMessage(),
        ]);

        CourierOrder::where('order_id', $this->orderId)->update([
            'status'        => 'failed',
            'error_message' => 'Max retries reached: ' . $e->getMessage(),
        ]);
    }
}
