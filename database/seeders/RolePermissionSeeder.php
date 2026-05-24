<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        // ── Permissions ──────────────────────────────────────────────────────
        $all = [
            // Products
            'view_products', 'create_products', 'edit_products', 'delete_products',
            // Categories
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            // Orders
            'view_orders', 'update_order_status', 'cancel_orders',
            // Payments
            'view_payments', 'verify_payments', 'reject_payments',
            // Coupons
            'view_coupons', 'create_coupons', 'edit_coupons', 'delete_coupons',
            // Content (Banners, Blog Posts)
            'view_content', 'create_content', 'edit_content', 'delete_content',
            // Contact
            'view_contact',
            // Newsletter
            'manage_newsletter',
            // Users
            'view_users', 'create_users', 'edit_users', 'delete_users',
            // Reviews
            'view_reviews', 'approve_reviews', 'delete_reviews',
            // Shipping
            'view_shipping', 'manage_shipping',
            // Courier
            'view_courier_orders', 'manage_courier_orders', 'manage_courier_settings',
            // Settings
            'view_settings', 'edit_settings',
            // Roles
            'view_roles', 'manage_roles',
        ];

        // Remove any stale permissions no longer in the canonical list
        Permission::where('guard_name', $guard)->whereNotIn('name', $all)->delete();

        foreach ($all as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => $guard]);
        }

        // ── Roles ─────────────────────────────────────────────────────────────

        // Super Admin — everything (Gate::before already grants all; explicit sync kept for consistency)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $superAdmin->syncPermissions($all);

        // Admin — all except staff/role management
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $admin->syncPermissions(array_values(array_filter(
            $all,
            fn ($p) => ! in_array($p, ['view_roles', 'manage_roles'], true)
        )));

        // Staff — read + operational tasks only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => $guard]);
        $staff->syncPermissions([
            'view_products', 'view_categories',
            'view_orders', 'update_order_status',
            'view_payments', 'verify_payments', 'reject_payments',
            'view_users',
            'view_reviews', 'approve_reviews',
            'view_shipping',
            'view_content',
            'view_contact',
            'view_courier_orders',
        ]);

        // Customer — no admin permissions
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => $guard]);

        $this->command->info('  Roles & permissions seeded.');
    }
}
