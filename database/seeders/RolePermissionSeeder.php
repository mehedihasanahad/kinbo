<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';

        // ── Permissions ──────────────────────────────────────────────────────
        $permissions = [
            // Products
            'view_products', 'create_products', 'edit_products', 'delete_products',
            // Categories
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            // Brands
            'view_brands', 'create_brands', 'edit_brands', 'delete_brands',
            // Orders
            'view_orders', 'update_order_status', 'cancel_orders',
            // Payments
            'view_payments', 'verify_payments', 'reject_payments',
            // Coupons
            'view_coupons', 'create_coupons', 'edit_coupons', 'delete_coupons',
            // Customers
            'view_customers', 'edit_customers', 'ban_customers',
            // Reviews
            'view_reviews', 'approve_reviews', 'delete_reviews',
            // Shipping
            'view_shipping', 'manage_shipping',
            // Settings
            'view_settings', 'edit_settings',
            // Staff
            'view_staff', 'manage_staff',
            // Reports
            'view_reports',
        ];

        $permissionModels = [];
        foreach ($permissions as $name) {
            $permissionModels[$name] = Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard]
            );
        }

        // ── Roles & assignments ───────────────────────────────────────────────

        // Super Admin — everything
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $superAdmin->permissions()->sync(array_column($permissionModels, 'id'));

        // Admin — all except manage_staff
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $adminPerms = array_filter($permissionModels, fn($k) => $k !== 'manage_staff', ARRAY_FILTER_USE_KEY);
        $admin->permissions()->sync(array_column($adminPerms, 'id'));

        // Staff — limited operational permissions
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => $guard]);
        $staffPerms = [
            'view_products', 'view_categories', 'view_brands',
            'view_orders', 'update_order_status',
            'view_payments', 'verify_payments', 'reject_payments',
            'view_customers',
            'view_reviews', 'approve_reviews',
            'view_shipping',
            'view_reports',
        ];
        $staff->permissions()->sync(
            array_map(fn($k) => $permissionModels[$k]->id, $staffPerms)
        );

        // Customer — no admin permissions (role used for identification only)
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => $guard]);

        $this->command->info('  Roles & permissions seeded.');
    }
}
