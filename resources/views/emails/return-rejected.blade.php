@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p class="lead">We have reviewed your return request for order <strong>{{ $order->order_number }}</strong>. Unfortunately, we are unable to approve the return at this time.</p>

<div class="card card-danger">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Return Status</span>
        <span class="value"><span class="badge badge-danger">Not Approved</span></span>
    </div>
    <div class="card-row">
        <span class="label">Order Total</span>
        <span class="value">৳{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

@if($order->returnRequest?->admin_notes)
<div class="card card-neutral" style="margin-top:16px;">
    <p style="font-size:13px;color:#374151;font-weight:600;padding-top:6px;">Reason from our team:</p>
    <p style="font-size:13px;color:#6b7280;padding-bottom:6px;">{{ $order->returnRequest->admin_notes }}</p>
</div>
@endif

<p style="font-size:14px;color:#374151;margin-top:20px;line-height:1.7;">
    If you believe this decision was made in error or have further questions, please contact our support team — we're happy to help.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ route('orders.show', $order) }}" class="btn btn-gray">View Order</a>
</div>

<hr class="divider">
<p class="help-text">Thank you for your understanding. We hope to serve you better in the future.</p>
@endsection
