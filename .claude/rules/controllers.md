# Controllers

## Core Rule
Controllers are **thin orchestrators** — validate input, call a service, return a response. No business logic, no raw Eloquent queries beyond simple lookups.

## Structure
- Extend `App\Http\Controllers\Controller`.
- Use resource controllers (`index`, `show`, `create`, `store`, `edit`, `update`, `destroy`) for CRUD.
- Single-action controllers (`__invoke`) for actions that don't fit CRUD (e.g. `ApplyCouponController`).
- Never exceed ~50 lines per method. If it grows, extract to a Service or Action.

## Dependency Injection
- Inject services and repositories via method injection or constructor injection.
- Never use `app()`, `resolve()`, or `Container::make()` inside a controller.

```php
// Good — method injection
public function store(StoreProductRequest $request, ProductService $service): RedirectResponse
{
    $product = $service->create($request->validated());
    return redirect()->route('products.show', $product)->with('success', 'Product created.');
}
```

## Return Types
- Always declare return types: `View`, `RedirectResponse`, `JsonResponse`, `Response`, `StreamedResponse`.

## Input Handling
- Use Form Request classes for any form with more than two fields.
- **Always** use `$request->validated()` — never `$request->all()`, `$request->input()`, or `$request->only()` for store/update.
- Use `$request->boolean()`, `$request->integer()` helpers for type-safe access.

## Responses
- Web: `view()`, `redirect()->route()`, `back()->withErrors()`.
- API: `response()->json()` with appropriate HTTP status codes.
- Flash messages via `->with('success', ...)` or `->with('error', ...)`.

## Auth & Authorization
- Use `$this->authorize()` or Gate checks — never check roles/permissions with raw strings in controllers.
- Place `$this->authorize()` as the first line of any method that needs it.

## Group Naming
- Admin controllers: `app/Http/Controllers/Admin/`.
- Auth controllers (Breeze-generated): `app/Http/Controllers/Auth/` — do not modify.
- All other controllers: `app/Http/Controllers/`.
