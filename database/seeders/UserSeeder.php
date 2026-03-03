<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $guard     = 'web';
        $modelType = User::class;

        // ── Admins ────────────────────────────────────────────────────────────
        $adminAccounts = [
            [
                'role'  => 'super_admin',
                'name'  => 'Super Admin',
                'email' => 'superadmin@ecom.test',
                'phone' => '01700000001',
                'locale'=> 'en',
            ],
            [
                'role'  => 'admin',
                'name'  => 'Admin User',
                'email' => 'admin@ecom.test',
                'phone' => '01700000002',
                'locale'=> 'en',
            ],
            [
                'role'  => 'staff',
                'name'  => 'Staff Member',
                'email' => 'staff@ecom.test',
                'phone' => '01700000003',
                'locale'=> 'bn',
            ],
        ];

        foreach ($adminAccounts as $data) {
            $roleName = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'          => Hash::make('password'),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ])
            );

            $this->assignRole($user, $roleName, $guard, $modelType);
        }

        // ── Customers ─────────────────────────────────────────────────────────
        $customers = [
            [
                'name'    => 'Rahim Uddin',
                'email'   => 'rahim@example.com',
                'phone'   => '01811111111',
                'locale'  => 'bn',
                'address' => [
                    'label'          => 'Home',
                    'recipient_name' => 'Rahim Uddin',
                    'phone'          => '01811111111',
                    'address_line'   => '12/A Mirpur Road',
                    'city'           => 'Dhaka',
                    'district'       => 'Dhaka',
                    'upazila'        => 'Mirpur',
                    'zip_code'       => '1216',
                    'is_default'     => true,
                ],
            ],
            [
                'name'    => 'Karim Hossain',
                'email'   => 'karim@example.com',
                'phone'   => '01922222222',
                'locale'  => 'bn',
                'address' => [
                    'label'          => 'Office',
                    'recipient_name' => 'Karim Hossain',
                    'phone'          => '01922222222',
                    'address_line'   => '45 Agrabad C/A',
                    'city'           => 'Chittagong',
                    'district'       => 'Chattogram',
                    'upazila'        => 'Double Mooring',
                    'zip_code'       => '4100',
                    'is_default'     => true,
                ],
            ],
            [
                'name'    => 'Fatema Begum',
                'email'   => 'fatema@example.com',
                'phone'   => '01633333333',
                'locale'  => 'en',
                'address' => [
                    'label'          => 'Home',
                    'recipient_name' => 'Fatema Begum',
                    'phone'          => '01633333333',
                    'address_line'   => '23 Shaheb Bazar',
                    'city'           => 'Rajshahi',
                    'district'       => 'Rajshahi',
                    'upazila'        => 'Boalia',
                    'zip_code'       => '6000',
                    'is_default'     => true,
                ],
            ],
            [
                'name'    => 'Nusrat Jahan',
                'email'   => 'nusrat@example.com',
                'phone'   => '01744444444',
                'locale'  => 'en',
                'address' => [
                    'label'          => 'Home',
                    'recipient_name' => 'Nusrat Jahan',
                    'phone'          => '01744444444',
                    'address_line'   => 'House 7, Road 3, Block C, Uttara',
                    'city'           => 'Dhaka',
                    'district'       => 'Dhaka',
                    'upazila'        => 'Uttara',
                    'zip_code'       => '1230',
                    'is_default'     => true,
                ],
            ],
            [
                'name'    => 'Sohel Rana',
                'email'   => 'sohel@example.com',
                'phone'   => '01555555555',
                'locale'  => 'bn',
                'address' => [
                    'label'          => 'Home',
                    'recipient_name' => 'Sohel Rana',
                    'phone'          => '01555555555',
                    'address_line'   => '8 Station Road',
                    'city'           => 'Sylhet',
                    'district'       => 'Sylhet',
                    'upazila'        => 'Sadar',
                    'zip_code'       => '3100',
                    'is_default'     => true,
                ],
            ],
        ];

        foreach ($customers as $data) {
            $addressData = $data['address'];
            unset($data['address']);

            $customer = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'          => Hash::make('password'),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ])
            );

            $this->assignRole($customer, 'customer', $guard, $modelType);

            UserAddress::firstOrCreate(
                ['user_id' => $customer->id, 'address_line' => $addressData['address_line']],
                array_merge($addressData, ['user_id' => $customer->id])
            );
        }

        $this->command->info('  Users & addresses seeded.');
    }

    private function assignRole(User $user, string $roleName, string $guard, string $modelType): void
    {
        $role = Role::where('name', $roleName)->where('guard_name', $guard)->first();
        if (!$role) return;

        DB::table('model_has_roles')->updateOrInsert([
            'role_id'    => $role->id,
            'model_id'   => $user->id,
            'model_type' => $modelType,
        ]);
    }
}
