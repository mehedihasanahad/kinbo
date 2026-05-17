# Mailables

## One Mailable Per Email Event
Each transactional email has its own Mailable class. Current mailables:
- `OrderConfirmation`, `OrderShipped`, `OrderDelivered`, `OrderCancelled`
- `PaymentVerified`, `PaymentRejected`
- `ReturnApproved`, `ReturnRejected`
- `NewsletterMail`, `SubscribeConfirmationMail`

## Class Structure
Use the Laravel 9+ `envelope()` / `content()` API — never the old `build()` method.

```php
final class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $queue = 'emails';

    public function __construct(public readonly Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('mail.order_confirmed'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.orders.confirmation');
    }

    public function attachments(): array
    {
        return [];
    }
}
```

## Rules
- All Mailables must implement `ShouldQueue` — never send synchronously in a web request.
- Always dispatch via `Mail::to($user)->queue(new OrderConfirmation($order))`.
- Never call `Mail::send()` or `Mail::to()->send()` inline in controllers.
- Email subjects must use `__()` for translation.
- Pass the minimum required data to the constructor (model or ID) — not raw arrays.

## Templates
- Email views: `resources/views/emails/`.
- Use Markdown mailables (`markdown:`) for clean, responsive layouts.
- Customize Markdown mail components: `php artisan vendor:publish --tag=laravel-mail`.
- Test email rendering locally with Mailpit (configured in `.env` via `MAIL_MAILER=smtp`).

## Attachments
- Generate PDFs (DomPDF) as queued jobs, then attach to a Mailable dispatched after generation.
- Use `Attachment::fromStorageDisk('local', $path)` — never attach from a public URL.
- Set reasonable `max_size` expectations and warn in logs if attachments exceed 5MB.

## Testing
- Always use `Mail::fake()` in tests.
- Assert: `Mail::assertQueued(OrderConfirmation::class, fn($m) => $m->order->is($order))`.
