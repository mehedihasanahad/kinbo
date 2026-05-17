# Routing

## File Organization
| File | Purpose |
|---|---|
| `routes/web.php` | Browser-facing routes with sessions & CSRF |
| `routes/api.php` | Stateless API routes under `/api/v1/` |
| `routes/auth.php` | Breeze-generated auth routes — do not modify |
| `routes/console.php` | Artisan closures (avoid; use Command classes) |

## Naming
- Every route must have a name. Use `->name()` always.
- Use `route()` helper everywhere — never hardcode URLs.
- Name prefix mirrors URI prefix: `account.` prefix for `/account/` routes.

```php
Route::middleware(['auth', 'verified'])
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });
```

## Resource Routes
- Use `Route::resource()` for full CRUD; `Route::apiResource()` for API-only.
- Use `->only([])` or `->except([])` to expose only the needed verbs.
- Nest resources only one level deep: `/orders/{order}/items` — not deeper.

## Middleware
- Apply middleware groups explicitly; do not rely on global middleware for feature-specific auth.
- Rate limit auth and sensitive endpoints: `throttle:auth`, `throttle:api`.
- Locale middleware `SetLocale` is registered globally in `bootstrap/app.php` — do not re-apply per route.

## API Versioning
- All API routes prefixed with `/api/v1/`.
- Version bump to `/api/v2/` only for breaking changes; maintain v1 until clients migrate.

## No Inline Closures
- Never use closures in `routes/web.php` or `routes/api.php` for non-trivial logic — they can't be cached.
- Closures are allowed only for one-liner redirects or simple test scaffolding.

## Route Model Binding
- Use implicit route model binding by default.
- Use explicit binding in `RouteServiceProvider` only for custom resolution logic.
- Scope bindings with `->scopeBindings()` when child routes should be scoped to their parent.
