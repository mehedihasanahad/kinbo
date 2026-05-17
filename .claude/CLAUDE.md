# Laravel Development Rules — ecom-tech

## Project Overview

Laravel 12 e-commerce platform with a Filament 3 admin panel, Breeze auth, Socialite OAuth, multi-language support, and SSLCommerz payment integration.

- **PHP**: ^8.2
- **Laravel**: 12.x
- **Admin**: Filament 3.3
- **Auth**: Laravel Breeze + Socialite
- **Frontend**: Blade + Vite + Tailwind CSS
- **Testing**: PHPUnit 11 / Laravel test helpers
- **Code style**: Laravel Pint (PSR-12 + Laravel preset)

---

## Commands

```bash
# Development (server + queue + vite in parallel)
composer dev

# Run tests
composer test

# Code style fix
./vendor/bin/pint

# Artisan shortcuts
php artisan make:model Foo -mfsc      # model + migration + factory + seeder + controller
php artisan make:filament-resource Foo --generate
php artisan queue:work --tries=3
```

---

## Coding Standards

- **PSR-12** strictly. Run `./vendor/bin/pint` before committing.
- PHP 8.2+ features: readonly properties, enums, named arguments, match expressions, fibers where appropriate.
- **No `mixed` types** without justification. Use union types and generics (via docblocks) instead.
- Strict typing in every file: `declare(strict_types=1);`
- Use `final` on classes that must not be extended.
- Prefer `readonly` constructor property promotion for DTOs and value objects.

---

## Directory & Namespace Conventions

```
app/
  Console/Commands/        # Artisan commands (Verb + Noun: CreateAdminUser)
  Filament/
    Pages/                 # Custom Filament pages
    Resources/             # One resource per model; Pages/ subfolder per resource
    Widgets/               # Dashboard widgets
    Concerns/              # Shared Filament traits (HasResourcePermissions)
  Http/
    Controllers/           # Thin controllers; delegate to Services
      Admin/               # Admin-specific non-Filament controllers
      Auth/                # Breeze-generated auth controllers (do not modify)
    Middleware/
    Requests/              # Form Request classes for every non-trivial input
  Jobs/                    # Queueable jobs (SendNewsletterJob)
  Mail/                    # Mailables — one per email event
  Models/                  # Eloquent models
  Observers/               # Model observers — register in AppServiceProvider
  Providers/               # Service providers
  Services/                # Business logic (ImageProcessor, SslCommerzService)
  View/Components/         # Blade components
```

---

## Models

- Every model must have `$fillable` **or** `$guarded = []` explicitly set — never rely on defaults.
- Define all relationships as typed methods with return types.
- Use Eloquent casts for dates, enums, JSON, and booleans.
- Define `$casts`, `$hidden`, and `$appends` explicitly.
- Scope methods must be prefixed with `scope` and return `Builder`.
- Use model factories for all models that have seeders or tests.
- Translation models (e.g. `CategoryTranslation`) must be used via a `translations()` relationship; never inline raw JSON in the parent.

```php
// Good
protected $casts = [
    'is_active'  => 'boolean',
    'metadata'   => 'array',
    'expires_at' => 'datetime',
    'status'     => OrderStatus::class,  // enum cast
];

// Bad — no casts, trusting raw DB values
```

---

## Controllers

- Controllers must be **thin**: validate → call service/action → return response.
- No business logic, no raw DB queries, no Eloquent calls beyond simple lookups.
- Use Form Request classes for any input with more than two fields.
- Resource controllers follow standard REST (`index`, `show`, `create`, `store`, `edit`, `update`, `destroy`).
- Return types: `View`, `RedirectResponse`, `JsonResponse`, `Response` — always typed.

```php
// Good
public function store(StoreProductRequest $request, ProductService $service): RedirectResponse
{
    $service->create($request->validated());
    return redirect()->route('products.index')->with('success', 'Product created.');
}
```

---

## Form Requests

- Every form submit that goes beyond a single field needs a dedicated `FormRequest`.
- `authorize()` returns `true` for guest-accessible routes; use Gate/Policy for protected ones.
- `rules()` must be exhaustive — validate every expected field.
- Add `messages()` and `attributes()` overrides to keep error messages user-friendly.
- Use `prepareForValidation()` to normalize input before rules run.

---

## Services

- Services hold all reusable business logic.
- Services are injected via constructor DI or method injection — never `app()` or `resolve()` inside controllers.
- Services must not return Eloquent models directly to controllers when a DTO/array is sufficient.
- Wrap multi-step DB operations in `DB::transaction()`.
- Services must not echo, throw HTTP exceptions, or interact with the request/session directly.

```php
final class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);
            // side effects...
            return $product;
        });
    }
}
```

---

## Observers

- Register observers in `AppServiceProvider::boot()` via `Model::observe(Observer::class)`.
- Use observers for cross-cutting concerns: image cleanup, cache invalidation, audit logging.
- Keep each observer method focused on a single responsibility.
- Do **not** trigger HTTP calls or heavy jobs directly inside observers — dispatch a job instead.

---

## Jobs

- All jobs must implement `ShouldQueue`.
- Set `$tries`, `$backoff`, and `$timeout` explicitly.
- Use `$queue` property to assign the correct queue (`default`, `emails`, `heavy`).
- Implement `failed(Throwable $e)` on any job where failure must be logged or notified.
- Pass model IDs, not model instances, to job constructors to avoid serialization issues with large models.

```php
final class SendNewsletterJob implements ShouldQueue
{
    public int $tries = 3;
    public int $backoff = 60;
    public string $queue = 'emails';

    public function __construct(public readonly int $subscriberId) {}
}
```

---

## Mail

- One Mailable per email event (e.g. `OrderConfirmation`, `OrderShipped`).
- Mailables must use `envelope()` and `content()` methods (Laravel 9+ API).
- All email subjects must be translatable via `__()`.
- Use `markdown` or `view` for templates, stored under `resources/views/emails/`.
- Never use `Mail::send()` inline in controllers — always dispatch a Mailable via `Mail::to()->queue()`.

---

## Filament (Admin Panel)

- Each Eloquent model managed in admin gets its own `Resource` class under `app/Filament/Resources/`.
- Resource pages (`List`, `Create`, `Edit`, `View`) live in the `Pages/` subfolder of the resource.
- Use `HasResourcePermissions` concern for all resources that need fine-grained access control.
- Widgets go in `app/Filament/Widgets/`; register them in the resource or panel provider, not globally.
- Use Filament's built-in form components — do not mix raw Blade inside Filament forms.
- Custom pages go in `app/Filament/Pages/`; register in `AdminPanelProvider`.
- Navigation grouping and ordering must be set in the Resource's `navigationGroup()` and `navigationSort()`.

---

## Routes

- Web routes: `routes/web.php` — grouped by feature with `prefix` and `name` prefixes.
- Auth routes: kept in Breeze's generated auth routes file — do not move them.
- API routes: `routes/api.php` — use `apiResource()` for RESTful endpoints, versioned under `/api/v1/`.
- Avoid inline closures in routes files for any non-trivial logic.
- Always name every route; use `route()` helper everywhere — never hardcode URLs.
- Apply middleware groups explicitly: `auth`, `verified`, `throttle:api`.

```php
Route::middleware(['auth', 'verified'])
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
    });
```

---

## Database & Migrations

- Every migration must be reversible — implement `down()` fully.
- Use `unsignedBigInteger` or `foreignId()` for foreign keys with explicit `constrained()` and `cascadeOnDelete()` / `nullOnDelete()`.
- Index columns used in `WHERE`, `ORDER BY`, and `JOIN` clauses.
- Never change an existing migration — create a new one.
- Use soft deletes (`SoftDeletes` trait) on models where records must be recoverable.
- Store money as integers (paise/cents), never floats.
- Timestamps (`created_at`, `updated_at`) on every table unless explicitly unnecessary.

---

## Seeders & Factories

- Use factories for fake data; seeders for environment-specific fixture data.
- `DatabaseSeeder` calls `UserSeeder`, `CategorySeeder`, etc. in dependency order.
- Production-safe seeders must be idempotent (`firstOrCreate`, `updateOrCreate`).
- Factories must use `fake()` helper (not the static `Faker` facade) and define `definition()` completely.

---

## Testing

- Test classes mirror `app/` structure under `tests/Feature/` and `tests/Unit/`.
- Use `RefreshDatabase` for feature tests; `WithoutMiddleware` only when explicitly testing middleware separately.
- Every new route must have at least one feature test covering the happy path.
- Use `actingAs($user)` for authenticated tests; `$this->assertDatabaseHas()` to verify persistence.
- Mock external services (SSLCommerz, mail, storage) in tests — never hit real APIs.
- Test file naming: `{ClassName}Test.php`.

```php
it('stores a new product', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('products.store'), ProductFactory::new()->raw())
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseCount('products', 1);
});
```

---

## Security

- Always use `$request->validated()` — never `$request->all()` or `$request->input()` directly in store/update operations.
- Use `Gate` or `Policy` for authorization — never raw role string checks in controllers.
- Sanitize file uploads: validate MIME type, extension, and max size in Form Requests.
- Image processing must go through `ImageProcessor` service — never manipulate files inline.
- Never store secrets in code; use `.env` exclusively.
- CSRF protection is on by default for all web routes — do not disable it.
- Rate-limit auth endpoints; review `throttle` middleware configuration in `bootstrap/app.php`.
- Escape all output in Blade with `{{ }}` — use `{!! !!}` only for trusted, sanitized HTML.

---

## Localization

- All user-facing strings must use `__()` or `@lang()`.
- Language files live in `lang/{locale}/`.
- Translation models (`CategoryTranslation`, `ProductTranslation`) handle DB-stored translations.
- Use `SetLocale` middleware (already registered) to set `app()->setLocale()` from session/user preference.
- Never hardcode locale-specific formatting — use `Number::currency()`, `Carbon` locale methods.

---

## Images & Media

- All image uploads must be processed through `App\Services\ImageProcessor`.
- Store processed images in `storage/app/public/` and expose via `storage:link`.
- Use `ProductImageObserver` and `BrandObserver` for automatic cleanup on model deletion.
- Never store base64 images in the database.
- Intervention Image v3 API (`\Intervention\Image\Laravel\Facades\Image`) is the only image manipulation library.

---

## Performance

- Eager-load relationships — no N+1 queries. Use Laravel Telescope or `DB::listen` in dev.
- Cache expensive, frequently-read data (settings, categories, currencies) with tagged cache (`Cache::tags()`).
- Use `select()` to limit columns on large tables.
- Paginate all list queries with `paginate()` or `cursorPaginate()` — never `->get()` on unbounded sets.
- Dispatch heavy operations (PDF generation, bulk email, report exports) as queued jobs.

---

## Filament & Frontend Coding

- Tailwind CSS classes: follow utility-first — no custom CSS unless absolutely unavoidable.
- Blade components live in `app/View/Components/` with matching views in `resources/views/components/`.
- Alpine.js for lightweight interactivity — keep `x-data` objects small and focused.
- Vite handles asset bundling — import CSS/JS only via `@vite()` directive.
- No jQuery. No inline `<style>` or `<script>` tags in Blade templates.

---

## Git & PR Hygiene

- Branch naming: `feature/`, `fix/`, `chore/`, `refactor/` prefixes.
- One logical change per commit. Commit messages: imperative mood, present tense (`Add product variant support`).
- Run `composer test` and `./vendor/bin/pint` locally before pushing.
- PR descriptions must include: what changed, why, and how to test.
- Do not commit `.env`, `storage/`, `node_modules/`, or `vendor/`.
