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
        public ?string $locale = null, // null = all locales
    ) {}

    public function handle(): void
    {
        $query = Subscriber::active();

        if ($this->locale) {
            $query->where('locale', $this->locale);
        }

        $query->orderBy('id')->chunk(100, function ($subscribers) {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)
                    ->queue(new NewsletterMail($this->subject, $this->body, $subscriber));
            }
        });
    }
}
