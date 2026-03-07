<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscribeConfirmationMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Subscriber $subscriber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm your subscription to ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscribe-confirmation',
            with: [
                'confirmUrl'     => route('subscribe.confirm', $this->subscriber->token),
                'unsubscribeUrl' => route('subscribe.unsubscribe', $this->subscriber->token),
                'appName'        => config('app.name'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
