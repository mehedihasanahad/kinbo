# API Development

## Versioning
- All API routes under `/api/v1/`.
- Breaking changes require a new version prefix (`/api/v2/`) — maintain v1 until all clients migrate.

## Routes
- Use `Route::apiResource()` for RESTful resources — skip `create` and `edit` (web-only).
- Apply `throttle:api` middleware to all API route groups.
- Authenticate with Laravel Sanctum — `auth:sanctum` middleware.

```php
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('products', Api\ProductController::class);
});
```

## Controllers
- API controllers extend `App\Http\Controllers\Controller`.
- Place in `app/Http/Controllers/Api/` namespace.
- Always return typed `JsonResponse`.

## Responses
Use consistent response structure across all endpoints:

```json
// Success
{ "data": { ... }, "message": "Product created." }

// Collection
{ "data": [ ... ], "meta": { "current_page": 1, "last_page": 5, "total": 48 } }

// Error
{ "message": "The given data was invalid.", "errors": { "field": ["error"] } }
```

## API Resources
- Use `JsonResource` and `ResourceCollection` for all API responses — never return raw Eloquent models.
- Place in `app/Http/Resources/`.
- Conditional fields: `$this->when($condition, $value)`.
- Nested relationships: use `->load()` in the controller and include with `new RelationshipResource(...)`.

```php
final class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'price'      => $this->price,
            'category'   => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
```

## Status Codes
| Scenario | Code |
|---|---|
| Successful GET / PUT | 200 |
| Resource created (POST) | 201 |
| Accepted (async) | 202 |
| No content (DELETE) | 204 |
| Validation error | 422 |
| Unauthenticated | 401 |
| Forbidden | 403 |
| Not found | 404 |
| Server error | 500 |

## Validation
- Always use Form Requests for API endpoints — same rules as web forms.
- Validation errors automatically return `422` with an `errors` object.

## Pagination
- Always paginate list endpoints: `paginate(15)` or `cursorPaginate(15)`.
- Never return unbounded collections.
- Wrap with `ResourceCollection` to get standardized `meta` and `links`.

## Error Handling
- Handle `ModelNotFoundException` globally in `bootstrap/app.php` → return 404 JSON.
- Handle `AuthenticationException` → return 401 JSON for API routes.
- Do not expose stack traces or internal messages in production API responses.
