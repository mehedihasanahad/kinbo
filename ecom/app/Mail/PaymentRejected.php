<?php

namespace App\Mail;

use App\Models\ManualPayment;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public ManualPayment $payment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Issue – Order ' . $this->order->order_number . ' | ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-rejected',
        );
    }
}
