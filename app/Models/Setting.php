<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'is_public'];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever('setting.' . $key, function () use ($key) {
            return static::where('key', $key)->value('value') ?? '__null__';
        });

        return $value === '__null__' ? $default : $value;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        Cache::forget('setting.' . $key);
        Cache::forget('settings.all');
        Cache::forget('settings.public');
    }

    public static function allPublic(): array
    {
        return Cache::rememberForever('settings.public', function () {
            return static::where('is_public', true)->pluck('value', 'key')->toArray();
        });
    }

    public static function group(string $group): \Illuminate\Support\Collection
    {
        return static::where('group', $group)->pluck('value', 'key');
    }
}
