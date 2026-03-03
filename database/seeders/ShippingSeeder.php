<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use App\Models\ShippingZone;
use App\Models\ShippingZoneDistrict;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name'        => 'Dhaka City',
                'description' => 'Inside Dhaka metropolitan area',
                'districts'   => ['Dhaka'],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 60.00,
                        'free_shipping_above'  => 1000.00,
                        'estimated_days_min'   => 1,
                        'estimated_days_max'   => 2,
                    ],
                    [
                        'method_name'          => 'Express Delivery',
                        'cost'                 => 120.00,
                        'free_shipping_above'  => null,
                        'estimated_days_min'   => 0,
                        'estimated_days_max'   => 1,
                    ],
                    [
                        'method_name'          => 'Same Day Delivery',
                        'cost'                 => 200.00,
                        'free_shipping_above'  => null,
                        'estimated_days_min'   => 0,
                        'estimated_days_max'   => 0,
                    ],
                ],
            ],
            [
                'name'        => 'Dhaka Division (Outside City)',
                'description' => 'Gazipur, Narayanganj, Manikganj, Munshiganj, Narsingdi, Tangail, Faridpur, Madaripur, Shariatpur, Rajbari, Gopalganj',
                'districts'   => [
                    'Gazipur', 'Narayanganj', 'Manikganj', 'Munshiganj',
                    'Narsingdi', 'Tangail', 'Faridpur', 'Madaripur',
                    'Shariatpur', 'Rajbari', 'Gopalganj',
                ],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 100.00,
                        'free_shipping_above'  => 2000.00,
                        'estimated_days_min'   => 1,
                        'estimated_days_max'   => 3,
                    ],
                    [
                        'method_name'          => 'Express Delivery',
                        'cost'                 => 180.00,
                        'free_shipping_above'  => null,
                        'estimated_days_min'   => 1,
                        'estimated_days_max'   => 2,
                    ],
                ],
            ],
            [
                'name'        => 'Chittagong Division',
                'description' => 'Chattogram, Cox\'s Bazar, Comilla, Feni, Noakhali, Lakshmipur, Chandpur, Brahmanbaria, Rangamati, Khagrachhari, Bandarban',
                'districts'   => [
                    'Chattogram', "Cox's Bazar", 'Comilla', 'Feni',
                    'Noakhali', 'Lakshmipur', 'Chandpur', 'Brahmanbaria',
                    'Rangamati', 'Khagrachhari', 'Bandarban',
                ],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 120.00,
                        'free_shipping_above'  => 2500.00,
                        'estimated_days_min'   => 2,
                        'estimated_days_max'   => 4,
                    ],
                    [
                        'method_name'          => 'Express Delivery',
                        'cost'                 => 220.00,
                        'free_shipping_above'  => null,
                        'estimated_days_min'   => 1,
                        'estimated_days_max'   => 2,
                    ],
                ],
            ],
            [
                'name'        => 'Sylhet Division',
                'description' => 'Sylhet, Moulvibazar, Habiganj, Sunamganj',
                'districts'   => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 130.00,
                        'free_shipping_above'  => 2500.00,
                        'estimated_days_min'   => 2,
                        'estimated_days_max'   => 4,
                    ],
                    [
                        'method_name'          => 'Express Delivery',
                        'cost'                 => 230.00,
                        'free_shipping_above'  => null,
                        'estimated_days_min'   => 1,
                        'estimated_days_max'   => 2,
                    ],
                ],
            ],
            [
                'name'        => 'Rajshahi Division',
                'description' => 'Rajshahi, Chapai Nawabganj, Natore, Naogaon, Bogura, Joypurhat, Sirajganj, Pabna',
                'districts'   => [
                    'Rajshahi', 'Chapai Nawabganj', 'Natore', 'Naogaon',
                    'Bogura', 'Joypurhat', 'Sirajganj', 'Pabna',
                ],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 120.00,
                        'free_shipping_above'  => 2500.00,
                        'estimated_days_min'   => 2,
                        'estimated_days_max'   => 4,
                    ],
                ],
            ],
            [
                'name'        => 'Khulna Division',
                'description' => 'Khulna, Bagerhat, Satkhira, Jessore, Magura, Jhenaidah, Narail, Kushtia, Chuadanga, Meherpur',
                'districts'   => [
                    'Khulna', 'Bagerhat', 'Satkhira', 'Jessore', 'Magura',
                    'Jhenaidah', 'Narail', 'Kushtia', 'Chuadanga', 'Meherpur',
                ],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 120.00,
                        'free_shipping_above'  => 2500.00,
                        'estimated_days_min'   => 2,
                        'estimated_days_max'   => 4,
                    ],
                ],
            ],
            [
                'name'        => 'Barisal Division',
                'description' => 'Barisal, Bhola, Patuakhali, Pirojpur, Jhalokati, Barguna',
                'districts'   => ['Barisal', 'Bhola', 'Patuakhali', 'Pirojpur', 'Jhalokati', 'Barguna'],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 130.00,
                        'free_shipping_above'  => 3000.00,
                        'estimated_days_min'   => 3,
                        'estimated_days_max'   => 5,
                    ],
                ],
            ],
            [
                'name'        => 'Mymensingh Division',
                'description' => 'Mymensingh, Jamalpur, Sherpur, Netrokona',
                'districts'   => ['Mymensingh', 'Jamalpur', 'Sherpur', 'Netrokona'],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 110.00,
                        'free_shipping_above'  => 2000.00,
                        'estimated_days_min'   => 2,
                        'estimated_days_max'   => 3,
                    ],
                ],
            ],
            [
                'name'        => 'Rangpur Division',
                'description' => 'Rangpur, Dinajpur, Thakurgaon, Panchagarh, Nilphamari, Lalmonirhat, Gaibandha, Kurigram',
                'districts'   => [
                    'Rangpur', 'Dinajpur', 'Thakurgaon', 'Panchagarh',
                    'Nilphamari', 'Lalmonirhat', 'Gaibandha', 'Kurigram',
                ],
                'rates'       => [
                    [
                        'method_name'          => 'Standard Delivery',
                        'cost'                 => 140.00,
                        'free_shipping_above'  => 3000.00,
                        'estimated_days_min'   => 3,
                        'estimated_days_max'   => 5,
                    ],
                ],
            ],
        ];

        foreach ($zones as $zoneData) {
            $districts = $zoneData['districts'];
            $rates     = $zoneData['rates'];
            unset($zoneData['districts'], $zoneData['rates']);

            $zone = ShippingZone::firstOrCreate(
                ['name' => $zoneData['name']],
                array_merge($zoneData, ['is_active' => true])
            );

            foreach ($districts as $district) {
                ShippingZoneDistrict::firstOrCreate(
                    ['zone_id' => $zone->id, 'district_name' => $district]
                );
            }

            foreach ($rates as $rateData) {
                ShippingRate::firstOrCreate(
                    ['zone_id' => $zone->id, 'method_name' => $rateData['method_name']],
                    array_merge($rateData, ['zone_id' => $zone->id, 'is_active' => true])
                );
            }
        }

        $this->command->info('  Shipping zones & rates seeded (all 9 BD divisions).');
    }
}
