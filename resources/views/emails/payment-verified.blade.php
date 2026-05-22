@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p class="lead">Great news! Your payment for order <strong>{{ $order->order_number }}</strong> has been verified. Your order is now being processed and will be shipped soon.</p>

<div class="card">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Payment Status</span>
        <span class="value"><span class="badge badge-success">Paid</span></span>
    </div>
    <div class="card-row">
        <span class="label">Order Status</span>
        <span class="value"><span class="badge badge-info">Processing</span></span>
    </div>
    <div class="card-row">
        <span class="label">Total Paid</span>
        <span class="value total">৳{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

<p style="font-size:14px;color:#374151;margin-top:20px;line-height:1.7;">
    We will notify you once your order has been shipped. You can track your order status anytime from your account.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ route('orders.show', $order) }}" class="btn">View My Order</a>
</div>

<hr class="divider">
<p class="help-text">Thank you for shopping with us!</p>
@endsection
