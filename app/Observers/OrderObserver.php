<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\DispatchCourierOrderJob;
use App\Models\Order;
use App\Models\Setting;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if (! (bool) Setting::get('courier_auto_dispatch_enabled', '0')) {
            return;
        }

        $trigger  = Setting::get('courier_auto_dispatch_trigger', Order::STATUS_PROCESSING);
        $provider = Setting::get('courier_auto_dispatch_provider', 'steadfast');

        if ($order->status !== $trigger) {
            return;
        }

        // Skip if a courier order already exists (avoid double-dispatch)
        if ($order->courierOrder()->exists()) {
            return;
        }

        DispatchCourierOrderJob::dispatch($order->id, $provider);
    }
}
