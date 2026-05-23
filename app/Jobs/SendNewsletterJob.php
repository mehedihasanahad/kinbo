<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public string $subject,
        public string $body,
        public ?array $subscriberIds = null, // null = all active subscribers
    ) {}

    public function handle(): void
    {
        $query = Subscriber::active();

        if ($this->subscriberIds) {
            $query->whereIn('id', $this->subscriberIds);
        }

        $query->orderBy('id')->chunk(100, function ($subscribers) {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->send(new NewsletterMail($this->subject, $this->body, $subscriber));
            }
        });
    }
}
