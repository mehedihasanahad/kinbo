<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            // ── Welcome / New-customer ─────────────────────────────────────
            [
                'code'                => 'WELCOME100',
                'type'                => 'fixed',
                'value'               => 100.00,
                'min_order_amount'    => 500.00,
                'max_discount_amount' => null,
                'max_uses'            => null,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => null,
                'is_active'           => true,
            ],
            [
                'code'                => 'NEW200',
                'type'                => 'fixed',
                'value'               => 200.00,
                'min_order_amount'    => 1000.00,
                'max_discount_amount' => null,
                'max_uses'            => 500,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => now()->addMonths(3),
                'is_active'           => true,
            ],

            // ── Percentage discounts ───────────────────────────────────────
            [
                'code'                => 'SAVE10',
                'type'                => 'percent',
                'value'               => 10.00,
                'min_order_amount'    => 800.00,
                'max_discount_amount' => 500.00,
                'max_uses'            => null,
                'per_user_limit'      => 3,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => null,
                'is_active'           => true,
            ],
            [
                'code'                => 'MEGA15',
                'type'                => 'percent',
                'value'               => 15.00,
                'min_order_amount'    => 2000.00,
                'max_discount_amount' => 1500.00,
                'max_uses'            => 1000,
                'per_user_limit'      => 2,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => now()->addMonth(),
                'is_active'           => true,
            ],
            [
                'code'                => 'SPECIAL20',
                'type'                => 'percent',
                'value'               => 20.00,
                'min_order_amount'    => 5000.00,
                'max_discount_amount' => 2000.00,
                'max_uses'            => 200,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => now()->addDays(15),
                'is_active'           => true,
            ],

            // ── Eid/Festival specials ──────────────────────────────────────
            [
                'code'                => 'EID500',
                'type'                => 'fixed',
                'value'               => 500.00,
                'min_order_amount'    => 3000.00,
                'max_discount_amount' => null,
                'max_uses'            => 300,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => now()->addMonths(2),
                'expires_at'          => now()->addMonths(3),
                'is_active'           => true,
            ],
            [
                'code'                => 'POHELA25',
                'type'                => 'percent',
                'value'               => 25.00,
                'min_order_amount'    => 1500.00,
                'max_discount_amount' => 1000.00,
                'max_uses'            => 500,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => now()->addDays(30),
                'expires_at'          => now()->addDays(37),
                'is_active'           => true,
            ],

            // ── Electronics category-specific ─────────────────────────────
            [
                'code'                => 'TECH10',
                'type'                => 'percent',
                'value'               => 10.00,
                'min_order_amount'    => 10000.00,
                'max_discount_amount' => 3000.00,
                'max_uses'            => null,
                'per_user_limit'      => 2,
                'product_ids'         => null,
                'category_ids'        => null, // populated after categories seeded
                'starts_at'           => null,
                'expires_at'          => null,
                'is_active'           => true,
            ],

            // ── Free shipping coupon ───────────────────────────────────────
            [
                'code'                => 'FREESHIP',
                'type'                => 'fixed',
                'value'               => 150.00,
                'min_order_amount'    => 700.00,
                'max_discount_amount' => 150.00,
                'max_uses'            => null,
                'per_user_limit'      => 2,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => null,
                'expires_at'          => null,
                'is_active'           => true,
            ],

            // ── Expired/inactive (for testing edge cases) ─────────────────
            [
                'code'                => 'EXPIRED50',
                'type'                => 'fixed',
                'value'               => 50.00,
                'min_order_amount'    => 200.00,
                'max_discount_amount' => null,
                'max_uses'            => 100,
                'per_user_limit'      => 1,
                'product_ids'         => null,
                'category_ids'        => null,
                'starts_at'           => now()->subMonths(2),
                'expires_at'          => now()->subMonth(),
                'is_active'           => false,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        $this->command->info('  Coupons seeded (' . count($coupons) . ' coupons).');
    }
}
