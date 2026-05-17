# Services

## Purpose
Services contain all reusable business logic that belongs neither in controllers nor in models.

## Class Design
- Mark service classes `final`.
- One service per aggregate root (e.g. `ProductService`, `OrderService`, `CartService`).
- Constructor-inject dependencies; avoid `new ClassName()` inside a service.
- Register in `AppServiceProvider` or a dedicated `ServiceProvider` if it needs binding.

## Rules
- Services must **not** throw HTTP exceptions (`abort()`, `HttpException`) — let the controller handle HTTP semantics.
- Services must **not** access `request()`, `session()`, or `auth()` helpers directly — pass data in as arguments.
- Services must **not** echo, dump, or interact with the response layer.
- Wrap all multi-step DB operations in `DB::transaction()`.
- Return domain objects, DTOs, or primitives — not HTTP responses.

```php
final class OrderService
{
    public function __construct(
        private readonly CartService $cart,
        private readonly InventoryService $inventory,
    ) {}

    public function place(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $order = Order::create([...$data, 'user_id' => $user->id]);
            $this->inventory->decrement($order);
            $this->cart->clear($user);
            return $order;
        });
    }
}
```

## Existing Services
- `App\Services\ImageProcessor` — all image manipulation must go through this. Never use Intervention Image directly in controllers or models.
- `App\Services\SslCommerzService` — all SSLCommerz payment gateway calls go through this service only.

## Actions (Single-Method Services)
- For one-off operations, use a single-method class with `__invoke` instead of a full service.
- Naming: `PlaceOrderAction`, `ApplyCouponAction`, `GenerateInvoiceAction`.
