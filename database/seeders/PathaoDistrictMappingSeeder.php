<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PathaoDistrictMapping;
use App\Models\ShippingZoneDistrict;
use Illuminate\Database\Seeder;

class PathaoDistrictMappingSeeder extends Seeder
{
    /**
     * Pathao live city_id and zone_id lookup keyed by district name.
     *
     * city_id and zone_id fetched directly from the Pathao live API:
     *   GET /aladdin/api/v1/countries/cities
     *   GET /aladdin/api/v1/cities/{city_id}/zones
     *
     * zone_id is the first (main) zone returned by the API for each city.
     * area_id is null — Pathao does not require it for most districts.
     * Update individual rows via Admin → Courier → Pathao District Mappings
     * if a specific zone or area is needed for finer delivery routing.
     */
    private array $pathaoIds = [
        // district_name (matches shipping_zone_districts.district_name)
        //                           city_id   zone_id   area_id
        'Bagerhat'              => [52,        156,      null],
        'Bandarban'             => [62,        714,      null],
        'Barguna'               => [34,        931,      null],
        'Barisal'               => [17,        911,      null],
        'Bhola'                 => [53,        157,      null],
        'Bogura'                => [9,         753,      null],
        'Brahmanbaria'          => [32,        2678,     null],
        'Chandpur'              => [8,         102,      null],
        'Chapai Nawabganj'      => [15,        558,      null],
        'Chattogram'            => [2,         2896,     null],
        'Chuadanga'             => [61,        647,      null],
        'Comilla'               => [5,         2019,     null],
        "Cox's Bazar"           => [11,        107,      null],
        'Dhaka'                 => [1,         298,      null],
        'Dinajpur'              => [35,        582,      null],
        'Faridpur'              => [18,        922,      null],
        'Feni'                  => [6,         568,      null],
        'Gaibandha'             => [38,        781,      null],
        'Gazipur'               => [22,        1732,     null],
        'Gopalganj'             => [56,        160,      null],
        'Habiganj'              => [30,        684,      null],
        'Jamalpur'              => [41,        871,      null],
        'Jessore'               => [19,        600,      null],
        'Jhalokati'             => [27,        128,      null],
        'Jhenaidah'             => [49,        465,      null],
        'Joypurhat'             => [48,        787,      null],
        'Khagrachhari'          => [63,        2715,     null],
        'Khulna'                => [20,        515,      null],
        'Kurigram'              => [55,        788,      null],
        'Kushtia'               => [28,        636,      null],
        'Lakshmipur'            => [40,        1526,     null],
        'Lalmonirhat'           => [57,        802,      null],
        'Madaripur'             => [43,        870,      null],
        'Magura'                => [60,        164,      null],
        'Manikganj'             => [16,        1196,     null],
        'Meherpur'              => [50,        610,      null],
        'Moulvibazar'           => [12,        676,      null],
        'Munshiganj'            => [23,        1896,     null],
        'Mymensingh'            => [26,        850,      null],
        'Naogaon'               => [46,        584,      null],
        'Narail'                => [54,        706,      null],
        'Narayanganj'           => [21,        1025,     null],
        'Narsingdi'             => [47,        2684,     null],
        'Natore'                => [14,        503,      null],
        'Netrokona'             => [44,        872,      null],
        'Nilphamari'            => [39,        640,      null],
        'Noakhali'              => [7,         620,      null],
        'Pabna'                 => [24,        1040,     null],
        'Panchagarh'            => [37,        806,      null],
        'Patuakhali'            => [29,        470,      null],
        'Pirojpur'              => [31,        906,      null],
        'Rajbari'               => [58,        947,      null],
        'Rajshahi'              => [4,         577,      null],
        'Rangamati'             => [59,        672,      null],
        'Rangpur'               => [25,        585,      null],
        'Satkhira'              => [51,        658,      null],
        'Shariatpur'            => [64,        662,      null],
        'Sherpur'               => [33,        526,      null],
        'Sirajganj'             => [10,        581,      null],
        'Sunamganj'             => [45,        834,      null],
        'Sylhet'                => [3,         2456,     null],
        'Tangail'               => [13,        535,      null],
        'Thakurgaon'            => [36,        951,      null],
    ];

    public function run(): void
    {
        $districts = ShippingZoneDistrict::orderBy('district_name')->pluck('district_name');

        foreach ($districts as $districtName) {
            $ids = $this->pathaoIds[$districtName] ?? null;

            if ($ids === null) {
                $this->command?->line("  <comment>SKIP</comment> No Pathao mapping defined for: {$districtName}");
                continue;
            }

            [$cityId, $zoneId, $areaId] = $ids;

            PathaoDistrictMapping::updateOrCreate(
                ['district_name' => $districtName],
                [
                    'pathao_city_id' => $cityId,
                    'pathao_zone_id' => $zoneId,
                    'pathao_area_id' => $areaId,
                ],
            );
        }
    }
}
