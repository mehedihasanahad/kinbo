# Jobs & Queues

## Every Job Must
- Implement `ShouldQueue`.
- Declare `$tries`, `$backoff`, and `$timeout` explicitly.
- Declare `$queue` to route to the correct worker queue.
- Implement `failed(Throwable $e)` when failure requires notification or cleanup.

```php
final class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;   // seconds between retries
    public int $timeout = 120;  // max execution time
    public string $queue = 'emails';

    public function __construct(public readonly int $subscriberId) {}

    public function handle(): void
    {
        $subscriber = Subscriber::findOrFail($this->subscriberId);
        // ...
    }

    public function failed(Throwable $e): void
    {
        Log::error('Newsletter job failed', ['subscriber' => $this->subscriberId, 'error' => $e->getMessage()]);
    }
}
```

## Dispatch Rules
- Pass **model IDs**, not model instances, to job constructors — avoids stale serialized data.
- Use `dispatch()` helper or `Job::dispatch()` — never `Queue::push()` directly.
- For deferred work: `Job::dispatch()->delay(now()->addMinutes(5))`.
- For chained work: `Bus::chain([...])->dispatch()`.
- For batch work: `Bus::batch([...])->then(...)->catch(...)->dispatch()`.

## Queue Names (this project)
| Queue | Purpose |
|---|---|
| `default` | General background tasks |
| `emails` | All mail sending (newsletters, order confirmations) |
| `heavy` | PDF generation, report exports, bulk operations |

## Heavy Operations — Always Queue
- PDF invoice/report generation (DomPDF)
- Bulk email sends
- Image processing batches
- Report exports
- Any operation over ~2 seconds

## Development
- Run queue worker: `php artisan queue:work --tries=3`
- Inspect failed jobs: `php artisan queue:failed`
- Retry failed: `php artisan queue:retry {id}`
- Flush failures: `php artisan queue:flush`
