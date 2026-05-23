<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class NewAccountCredentials extends Mailable
{
    public function __construct(
        public readonly User   $user,
        public readonly string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your ' . config('app.name') . ' account credentials');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-account-credentials',
            with: [
                'user'     => $this->user,
                'password' => $this->password,
                'loginUrl' => route('login'),
                'appName'  => config('app.name'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
