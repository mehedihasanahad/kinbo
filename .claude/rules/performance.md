# Performance

## N+1 Queries — Zero Tolerance
- Always eager-load relationships used in views or loops: `->with(['category', 'images'])`.
- Use Laravel Telescope (`php artisan telescope:install`) in dev to detect N+1 queries.
- Use `->withCount()` instead of counting in PHP.
- Use `->has()` / `->whereHas()` for existence checks — not loading the full relationship.

```php
// Bad
$products = Product::all();
foreach ($products as $p) { echo $p->category->name; } // N+1

// Good
$products = Product::with('category')->get();
```

## Query Optimization
- Use `->select(['id', 'name', 'price'])` to limit columns on wide tables.
- Paginate all list queries: `->paginate(20)` or `->cursorPaginate(20)` — never `->get()` on unbounded sets.
- Use `->chunk(200, fn($batch) => ...)` for large dataset processing in commands/jobs.
- Avoid `->whereHas()` on large tables without an index on the foreign key.
- Prefer `->whereIn()` over multiple `->orWhere()` calls.

## Caching
- Cache expensive, frequently-read data: site settings, navigation categories, currency rates.
- Use tagged cache (`Cache::tags(['products'])->put(...)`) for granular invalidation.
- Clear related cache tags in Observers or after service mutations.
- Cache TTL: short for volatile data (5–15 min), longer for static data (1–24 hr).
- Never cache user-specific data in shared cache keys — always include `user_id` in the key.

```php
$categories = Cache::tags(['categories'])->remember('nav_categories', 3600, fn() =>
    Category::active()->with('children')->get()
);
```

## Database Indexes
- Ensure indexes exist on all `WHERE`, `ORDER BY`, and `JOIN` columns.
- Add composite indexes for frequently combined filter columns.
- Review `EXPLAIN` output for slow queries before shipping a feature.

## Heavy Operations — Always Queue
- PDF generation (DomPDF)
- Bulk email / newsletters
- Image processing
- CSV/Excel report exports
- External API calls in response to user actions

## HTTP Layer
- Use `->defer()` for non-critical response side effects (Laravel 11+ `defer()`).
- Set appropriate HTTP cache headers on public, infrequently-changing pages.
- Compress responses — ensure `gzip`/`brotli` is enabled at the server level.

## Asset Performance
- Vite produces hashed, minified bundles — no manual cache-busting needed.
- Lazy-load images below the fold with `loading="lazy"`.
- Avoid loading all JS on pages that don't need it — use `@push('scripts')` to scope per page.

## Artisan Optimization (Production)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --optimize-autoloader --no-dev
```
