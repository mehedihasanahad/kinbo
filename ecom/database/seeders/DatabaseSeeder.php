<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed order matters — later seeders depend on earlier ones.
     *
     * 1. RolePermissionSeeder  — roles & permissions (no deps)
     * 2. UserSeeder            — users with role assignments & addresses (needs roles)
     * 3. BrandCategorySeeder   — brands, categories, translations (no deps)
     * 4. ProductSeeder         — products, variants, images, translations (needs categories + brands)
     * 5. ShippingSeeder        — zones, districts, rates (no deps)
     * 6. CouponSeeder          — coupons (no deps)
     * 7. OrderSeeder           — orders, items, payments, reviews (needs all above)
     * 8. SettingSeeder         — site config key-value store (no deps)
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('  ecom-tech Database Seeder');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            BrandCategorySeeder::class,
            ProductSeeder::class,
            ShippingSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            SettingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('  Seeding complete!');
        $this->command->info('');
        $this->command->info('  Test credentials:');
        $this->command->info('  Super Admin : superadmin@ecom.test / password');
        $this->command->info('  Admin       : admin@ecom.test / password');
        $this->command->info('  Staff       : staff@ecom.test / password');
        $this->command->info('  Customer    : rahim@example.com / password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
    }
}
