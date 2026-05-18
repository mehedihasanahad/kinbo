<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['parent_id', 'image', 'sort_order', 'is_active', 'show_in_nav'];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'show_in_nav' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation(string $locale = 'en'): BelongsTo
    {
        return $this->hasOne(CategoryTranslation::class)->where('locale', $locale);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true);
    }

    public function getTranslation(string $locale = 'en'): ?CategoryTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
