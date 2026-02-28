<?php

namespace Database\Seeders;

use App\Models\ManualPayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Review;
use App\Models\ShippingRate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas(
            'roles', fn($q) => $q->where('name', 'customer')
        )->with('addresses')->get();

        if ($customers->isEmpty()) {
            $this->command->warn('  No customers found — skipping order seeding.');
            return;
        }

        $products = Product::with(['translations', 'variants'])->where('is_active', true)->get();
        if ($products->isEmpty()) {
            $this->command->warn('  No products found — skipping order seeding.');
            return;
        }

        $adminUser = User::where('email', 'admin@ecom.test')->first();

        $scenarios = [
            // Delivered, paid via SSLCommerz, with review
            [
                'payment_method' => Order::METHOD_SSLCOMMERZ,
                'status'         => Order::STATUS_DELIVERED,
                'payment_status' => Order::PAYMENT_PAID,
                'with_review'    => true,
            ],
            // Delivered, paid via bKash manual
            [
                'payment_method' => Order::METHOD_BKASH,
                'status'         => Order::STATUS_DELIVERED,
                'payment_status' => Order::PAYMENT_PAID,
                'with_review'    => false,
            ],
            // Shipped, paid via Nagad manual
            [
                'payment_method' => Order::METHOD_NAGAD,
                'status'         => Order::STATUS_SHIPPED,
                'payment_status' => Order::PAYMENT_PAID,
                'with_review'    => false,
            ],
            // Processing, COD
            [
                'payment_method' => Order::METHOD_COD,
                'status'         => Order::STATUS_PROCESSING,
                'payment_status' => Order::PAYMENT_UNPAID,
                'with_review'    => false,
            ],
            // Pending, bKash unverified
            [
                'payment_method' => Order::METHOD_BKASH,
                'status'         => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING_VERIFICATION,
                'with_review'    => false,
            ],
            // Cancelled
            [
                'payment_method' => Order::METHOD_SSLCOMMERZ,
                'status'         => Order::STATUS_CANCELLED,
                'payment_status' => Order::PAYMENT_FAILED,
                'with_review'    => false,
            ],
        ];

        $dhakaRate = ShippingRate::where('method_name', 'Standard Delivery')
            ->whereHas('zone', fn($q) => $q->where('name', 'Dhaka City'))
            ->first();

        $orderCount = 0;

        foreach ($customers as $customer) {
            $address = $customer->addresses->first();
            if (!$address) continue;

            foreach ($scenarios as $scenario) {
                $orderProducts = $products->random(min(rand(1, 3), $products->count()));
                $subtotal = 0;

                $lineItems = [];
                foreach ($orderProducts as $product) {
                    $qty      = rand(1, 3);
                    $price    = (float) ($product->sale_price ?? $product->price);
                    $variant  = $product->variants->first();
                    if ($variant) {
                        $price += (float) $variant->price_modifier;
                    }
                    $lineSubtotal = round($price * $qty, 2);
                    $subtotal    += $lineSubtotal;

                    $enTrans = $product->translations->firstWhere('locale', 'en');
                    $lineItems[] = [
                        'product'       => $product,
                        'variant'       => $variant,
                        'product_name'  => $enTrans ? $enTrans->name : $product->sku,
                        'variant_label' => $variant ? $variant->label : null,
                        'unit_price'    => $price,
                        'quantity'      => $qty,
                        'subtotal'      => $lineSubtotal,
                    ];
                }

                $shippingAmount = $dhakaRate ? (float) $dhakaRate->cost : 80.00;
                $totalAmount    = round($subtotal + $shippingAmount, 2);

                $order = Order::create([
                    'order_number'    => Order::generateOrderNumber(),
                    'user_id'         => $customer->id,
                    'coupon_id'       => null,
                    'shipping_rate_id'=> $dhakaRate?->id,
                    'subtotal'        => $subtotal,
                    'discount_amount' => 0.00,
                    'shipping_amount' => $shippingAmount,
                    'tax_amount'      => 0.00,
                    'total_amount'    => $totalAmount,
                    'status'          => $scenario['status'],
                    'payment_status'  => $scenario['payment_status'],
                    'payment_method'  => $scenario['payment_method'],
                    'ship_name'       => $address->recipient_name,
                    'ship_phone'      => $address->phone,
                    'ship_address'    => $address->address_line,
                    'ship_city'       => $address->city,
                    'ship_district'   => $address->district,
                    'ship_zip'        => $address->zip_code,
                    'notes'           => null,
                    'tracking_number' => in_array($scenario['status'], [Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                        ? 'TRK-' . strtoupper(Str::random(8))
                        : null,
                    'created_at'      => now()->subDays(rand(1, 60)),
                    'updated_at'      => now()->subDays(rand(0, 5)),
                ]);

                // Order items
                foreach ($lineItems as $item) {
                    OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => $item['product']->id,
                        'variant_id'    => $item['variant']?->id,
                        'product_name'  => $item['product_name'],
                        'variant_label' => $item['variant_label'],
                        'unit_price'    => $item['unit_price'],
                        'quantity'      => $item['quantity'],
                        'subtotal'      => $item['subtotal'],
                    ]);
                }

                // Status history
                $this->seedStatusHistory($order, $adminUser);

                // Payment records
                $this->seedPaymentRecord($order, $customer);

                // Review for delivered orders
                if ($scenario['with_review'] && $scenario['status'] === Order::STATUS_DELIVERED) {
                    foreach ($lineItems as $item) {
                        Review::firstOrCreate(
                            ['product_id' => $item['product']->id, 'user_id' => $customer->id],
                            [
                                'order_id'      => $order->id,
                                'rating'        => rand(4, 5),
                                'title'         => 'Great product!',
                                'body'          => 'I am very happy with this purchase. Quality is excellent and delivery was fast.',
                                'is_approved'   => true,
                                'helpful_count' => rand(0, 12),
                            ]
                        );
                    }
                }

                $orderCount++;
            }
        }

        $this->command->info("  Orders seeded ({$orderCount} orders across " . $customers->count() . ' customers).');
    }

    private function seedStatusHistory(Order $order, ?User $admin): void
    {
        $flow = [Order::STATUS_PENDING];

        if (in_array($order->status, [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])) {
            $flow[] = Order::STATUS_PROCESSING;
        }
        if (in_array($order->status, [Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])) {
            $flow[] = Order::STATUS_SHIPPED;
        }
        if ($order->status === Order::STATUS_DELIVERED) {
            $flow[] = Order::STATUS_DELIVERED;
        }
        if ($order->status === Order::STATUS_CANCELLED) {
            $flow[] = Order::STATUS_CANCELLED;
        }

        foreach ($flow as $i => $status) {
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $status,
                'notes'      => $this->statusNote($status),
                'changed_by' => $status === Order::STATUS_PENDING ? null : $admin?->id,
                'created_at' => $order->created_at->addHours($i * 4),
            ]);
        }
    }

    private function statusNote(string $status): ?string
    {
        return match ($status) {
            Order::STATUS_PENDING    => 'Order placed by customer.',
            Order::STATUS_PROCESSING => 'Payment confirmed. Order is being prepared.',
            Order::STATUS_SHIPPED    => 'Order handed over to courier.',
            Order::STATUS_DELIVERED  => 'Order delivered successfully.',
            Order::STATUS_CANCELLED  => 'Order cancelled.',
            default                  => null,
        };
    }

    private function seedPaymentRecord(Order $order, User $customer): void
    {
        if ($order->payment_method === Order::METHOD_SSLCOMMERZ) {
            PaymentTransaction::create([
                'order_id'     => $order->id,
                'gateway'      => PaymentTransaction::GATEWAY_SSLCOMMERZ,
                'tran_id'      => 'SSL-' . strtoupper(Str::random(10)),
                'val_id'       => 'VAL-' . strtoupper(Str::random(12)),
                'bank_tran_id' => 'BANK-' . strtoupper(Str::random(8)),
                'amount'       => $order->total_amount,
                'currency'     => 'BDT',
                'card_type'    => collect(['Visa', 'Mastercard', 'bKash', 'Nexus'])->random(),
                'status'       => $order->payment_status === Order::PAYMENT_PAID
                    ? PaymentTransaction::STATUS_SUCCESS
                    : PaymentTransaction::STATUS_FAILED,
                'raw_response' => [
                    'status'    => 'VALID',
                    'tran_date' => $order->created_at->format('Y-m-d H:i:s'),
                    'amount'    => $order->total_amount,
                    'currency'  => 'BDT',
                ],
            ]);
        }

        if (in_array($order->payment_method, [Order::METHOD_BKASH, Order::METHOD_NAGAD])) {
            $status = match ($order->payment_status) {
                Order::PAYMENT_PAID                 => ManualPayment::STATUS_VERIFIED,
                Order::PAYMENT_PENDING_VERIFICATION => ManualPayment::STATUS_PENDING,
                default                             => ManualPayment::STATUS_PENDING,
            };

            $adminUser = User::where('email', 'admin@ecom.test')->first();

            ManualPayment::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'method'           => $order->payment_method,
                    'sender_number'    => $customer->phone ?? '018' . rand(10000000, 99999999),
                    'transaction_id'   => strtoupper(Str::random(10)),
                    'amount'           => $order->total_amount,
                    'screenshot_path'  => null,
                    'status'           => $status,
                    'verified_by'      => $status === ManualPayment::STATUS_VERIFIED ? $adminUser?->id : null,
                    'verified_at'      => $status === ManualPayment::STATUS_VERIFIED ? $order->created_at->addHours(2) : null,
                    'rejection_reason' => null,
                ]
            );
        }

        if ($order->payment_method === Order::METHOD_COD) {
            PaymentTransaction::create([
                'order_id'  => $order->id,
                'gateway'   => PaymentTransaction::GATEWAY_COD,
                'tran_id'   => null,
                'val_id'    => null,
                'amount'    => $order->total_amount,
                'currency'  => 'BDT',
                'status'    => PaymentTransaction::STATUS_PENDING,
                'raw_response' => null,
            ]);
        }
    }
}
