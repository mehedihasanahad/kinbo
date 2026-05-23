<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class ContactFormSubmission extends Mailable
{
    public function __construct(public readonly ContactSubmission $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message: ' . $this->submission->subject,
            replyTo: [$this->submission->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-submission');
    }
}
