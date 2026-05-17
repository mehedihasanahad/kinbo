# Observers & Events

## Observers

### Purpose
Use observers for model lifecycle side effects: cache invalidation, file cleanup, audit logging, denormalized data sync.

### Rules
- Register all observers in `AppServiceProvider::boot()`:
  ```php
  Product::observe(ProductObserver::class);
  ```
- One observer class per model.
- Keep each observer method focused on a single concern.
- Do **not** make HTTP calls or run heavy computations inside observers — dispatch a Job instead.
- Do not trigger observer methods that could cause infinite loops (e.g. saving a model inside its own `saved` event).

### Existing Observers
| Observer | Model | Responsibility |
|---|---|---|
| `BannerObserver` | Banner | Image cleanup on delete |
| `BrandObserver` | Brand | Image cleanup on delete |
| `CategoryObserver` | Category | Image/cache cleanup on delete |
| `ProductImageObserver` | ProductImage | File deletion when image record deleted |

### Adding a New Observer
1. Create: `php artisan make:observer FooObserver --model=Foo`
2. Implement only the lifecycle methods you need (`created`, `updated`, `deleted`, `restored`).
3. Register in `AppServiceProvider::boot()`.

## Events & Listeners

### When to Use Events
- Decouple domain actions from their side effects when multiple listeners react to one thing.
- Good candidates: `OrderPlaced`, `PaymentVerified`, `UserRegistered`.

### Naming
- Events: past-tense noun phrase — `OrderPlaced`, `PaymentFailed`, `ReviewApproved`.
- Listeners: verb phrase that describes the reaction — `SendOrderConfirmation`, `UpdateInventory`.

### Rules
- Events are plain data objects — no logic, just public properties set in the constructor.
- Listeners implement `ShouldQueue` when their work is non-trivial (sending email, updating remote systems).
- Register event-listener pairs in `EventServiceProvider` (or `AppServiceProvider` via `Event::listen()`).
- Use `$event->broadcastOn()` only if real-time broadcasting is needed — do not add it by default.

```php
// Event — plain data carrier
final class OrderPlaced
{
    public function __construct(public readonly Order $order) {}
}

// Listener — queued side effect
final class SendOrderConfirmation implements ShouldQueue
{
    public string $queue = 'emails';

    public function handle(OrderPlaced $event): void
    {
        Mail::to($event->order->user)->queue(new OrderConfirmation($event->order));
    }
}
```

## Choosing Between Observer and Event
| Scenario | Use |
|---|---|
| Single model's own lifecycle side effect | Observer |
| Same action must trigger multiple independent reactions | Event + Listeners |
| Side effect needs to be queued | Event + Queued Listener (or Observer that dispatches a Job) |
| Cross-model reaction to a domain action | Event + Listener |
