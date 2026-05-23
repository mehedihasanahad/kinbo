<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $body,
        public Subscriber $subscriber,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
            with: [
                'body'           => $this->inlineStyles($this->body),
                'appName'        => config('app.name'),
                'unsubscribeUrl' => route('subscribe.unsubscribe', $this->subscriber->token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    /**
     * Add inline styles to rich-text HTML elements and convert relative image
     * URLs to absolute so they render correctly in Gmail and other email clients.
     */
    private function inlineStyles(string $html): string
    {
        $map = [
            '/<p>/i'          => '<p style="margin:0 0 14px 0;font-size:15px;color:#374151;line-height:1.7;">',
            '/<h1>/i'         => '<h1 style="margin:20px 0 10px;font-size:22px;font-weight:700;color:#111827;">',
            '/<h2>/i'         => '<h2 style="margin:18px 0 10px;font-size:18px;font-weight:700;color:#111827;">',
            '/<h3>/i'         => '<h3 style="margin:16px 0 8px;font-size:16px;font-weight:700;color:#111827;">',
            '/<ul>/i'         => '<ul style="margin:10px 0 14px 20px;">',
            '/<ol>/i'         => '<ol style="margin:10px 0 14px 20px;">',
            '/<li>/i'         => '<li style="margin-bottom:5px;font-size:15px;color:#374151;">',
            '/<strong>/i'     => '<strong style="font-weight:700;">',
            '/<blockquote>/i' => '<blockquote style="border-left:3px solid #f9a8d4;padding-left:12px;color:#6b7280;margin:14px 0;">',
        ];

        $html = preg_replace(array_keys($map), array_values($map), $html);

        // Inline anchor colour
        $html = preg_replace('/<a\s/i', '<a style="color:#c4155c;" ', $html);

        // Fix images: absolute URL + inline sizing styles
        $html = preg_replace_callback('/<img([^>]*)>/i', function ($m) {
            $attrs = $m[1];

            if (! str_contains($attrs, 'style=')) {
                $attrs .= ' style="max-width:100%;height:auto;display:block;border-radius:6px;margin:12px 0;"';
            }

            // Convert relative src to absolute
            $attrs = preg_replace_callback(
                '/src=["\'](?!https?:\/\/|data:)([^"\']+)["\']/i',
                fn ($s) => 'src="' . url($s[1]) . '"',
                $attrs
            );

            return '<img' . $attrs . '>';
        }, $html);

        return $html;
    }
}
