@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p style="font-size:15px;color:#374151;margin-bottom:20px;">
    We're sorry to inform you that your order <strong>{{ $order->order_number }}</strong> has been cancelled.
</p>

<div class="card" style="background:#fff1f2;border-color:#fecdd3;">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Order Date</span>
        <span class="value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
    </div>
    <div class="card-row">
        <span class="label">Status</span>
        <span class="value"><span class="badge badge-danger">Cancelled</span></span>
    </div>
    <div class="card-row">
        <span class="label">Order Total</span>
        <span class="value">৳{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

@if($order->payment_status === 'paid')
<div class="card" style="background:#fffbeb;border-color:#fcd34d;margin-top:16px;">
    <p style="font-size:13px;color:#92400e;">
        <strong>Refund Notice:</strong> Since your payment was already processed, a refund will be initiated within 3–5 business days. Please contact us if you have any questions.
    </p>
</div>
@endif

<p style="font-size:14px;color:#374151;margin-top:20px;">
    If you did not request this cancellation or have any concerns, please reach out to our support team right away.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ url('/products') }}" class="btn" style="background:#6b7280;">Continue Shopping</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#6b7280;">We hope to serve you again. Thank you for choosing {{ config('app.name') }}.</p>
@endsection
