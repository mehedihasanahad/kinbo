# Testing

## Framework
- PHPUnit 11 via Laravel test helpers (`php artisan test` / `composer test`).
- Feature tests for HTTP flows; Unit tests for pure business logic.

## File Structure
- Mirror `app/` under `tests/Feature/` and `tests/Unit/`.
- Naming: `{ClassName}Test.php`.

## Database
- Use `RefreshDatabase` trait on every feature test that touches the DB.
- Never use real database data in tests — build everything through factories.
- `assertDatabaseHas()`, `assertDatabaseMissing()`, `assertDatabaseCount()` for persistence checks.

## Authentication
- Use `actingAs($user)` for authenticated requests.
- Build user fixtures with factories: `User::factory()->create()`.
- Test role-specific access by creating users with the appropriate role/permission.

## HTTP Tests
- Every new route must have at least one feature test covering the happy path.
- Test both authenticated and unauthenticated access for protected routes.
- Assert HTTP status codes explicitly: `assertOk()`, `assertRedirect()`, `assertForbidden()`, `assertNotFound()`.
- Test validation failures with `assertSessionHasErrors(['field'])`.

```php
it('stores a new product', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('products.store'), Product::factory()->raw())
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseCount('products', 1);
});

it('rejects unauthenticated product creation', function () {
    $this->post(route('products.store'), [])->assertRedirect(route('login'));
});
```

## Mocking & Faking
- Mock external services (SSLCommerz, Socialite) — never hit real APIs in tests.
- Use Laravel fakes: `Mail::fake()`, `Queue::fake()`, `Event::fake()`, `Storage::fake()`, `Notification::fake()`.
- Assert dispatched jobs/mail/events after the action.

```php
it('dispatches newsletter job on subscribe', function () {
    Queue::fake();
    $this->post(route('subscribe'), ['email' => 'test@example.com']);
    Queue::assertPushed(SendNewsletterJob::class);
});
```

## Coverage Targets
- All Service methods: unit tested.
- All Form Requests: tested for both passing and failing validation.
- All critical paths (checkout, payment, order placement): feature tested end-to-end.
- All authorization rules: tested for allowed and denied cases.

## Test Isolation
- Tests must not depend on execution order.
- Never share mutable state between tests.
- Use `setUp()` only for shared test context that is reset per test.
