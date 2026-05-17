# Eloquent Models

## Mass Assignment
- Always define `$fillable` explicitly — or `$guarded = []` if all fields are safe.
- Never rely on default guard behavior.

## Casts
- Define `$casts` for every non-string column: booleans, dates, enums, JSON, decimals.
- Use backed PHP enums for status/type columns — cast directly to the enum class.
- Store money as integers (cents/paisa), never floats. Cast to `int`.

```php
protected $casts = [
    'is_active'  => 'boolean',
    'status'     => OrderStatus::class,
    'meta'       => 'array',
    'price'      => 'integer',
    'expires_at' => 'datetime',
];
```

## Relationships
- All relationship methods must have return type declarations.
- Use `belongsTo`, `hasMany`, `belongsToMany`, `morphTo`, etc. — never raw joins.
- Foreign key naming: `{model}_id` (e.g. `product_id`, `user_id`).
- Define the inverse of every relationship.

```php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function images(): HasMany
{
    return $this->hasMany(ProductImage::class);
}
```

## Scopes
- Local scopes prefixed with `scope`, return `Builder`.
- Name scopes descriptively: `scopeActive`, `scopePublished`, `scopeForUser`.

```php
public function scopeActive(Builder $query): Builder
{
    return $query->where('is_active', true);
}
```

## Soft Deletes
- Use `SoftDeletes` on any model whose records must be recoverable (Order, Product, User).
- Always filter deleted records explicitly in admin reports.

## Visibility
- Set `$hidden` for sensitive fields: `password`, `remember_token`, `api_token`.
- Use `$appends` only for computed attributes needed by the frontend.

## Observers
- Do not put side-effect logic (cache clearing, file deletion, notifications) inside model methods.
- Register observers in `AppServiceProvider::boot()` for all such concerns.

## Translation Models
- `CategoryTranslation` and `ProductTranslation` handle DB-stored i18n data.
- Always access translated fields via the relationship — never inline JSON in the parent model.
