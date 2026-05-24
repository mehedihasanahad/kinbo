<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoDistrictMapping extends Model
{
    protected $fillable = [
        'district_name',
        'pathao_city_id',
        'pathao_zone_id',
        'pathao_area_id',
    ];

    protected function casts(): array
    {
        return [
            'pathao_city_id' => 'integer',
            'pathao_zone_id' => 'integer',
            'pathao_area_id' => 'integer',
        ];
    }

    public static function findByDistrict(string $districtName): ?self
    {
        return static::whereRaw('LOWER(district_name) = ?', [strtolower(trim($districtName))])
            ->first();
    }
}
