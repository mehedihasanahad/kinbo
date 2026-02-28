<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'image',
        'locale',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'starts_at'  => 'datetime',
            'ends_at'    => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    /** Active banners matching a given locale (or 'all'). */
    public function scopeActive(Builder $query, string $locale = null): Builder
    {
        $locale ??= app()->getLocale();

        return $query
            ->where('is_active', true)
            ->where(fn ($q) => $q->where('locale', 'all')->orWhere('locale', $locale))
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image);
    }

}
