@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p style="font-size:15px;color:#374151;margin-bottom:20px;">
    Your order <strong>{{ $order->order_number }}</strong> has been delivered! We hope you love your purchase.
</p>

<div class="card" style="background:#f0fdf4;border-color:#bbf7d0;">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Status</span>
        <span class="value"><span class="badge badge-success">Delivered</span></span>
    </div>
    <div class="card-row">
        <span class="label">Delivered To</span>
        <span class="value">{{ $order->ship_name }}</span>
    </div>
    <div class="card-row">
        <span class="label">Total Paid</span>
        <span class="value total">৳{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

<p style="font-size:14px;color:#374151;margin-top:20px;">
    Enjoying your purchase? We'd love to hear what you think! Leave a review to help other shoppers.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ route('orders.show', $order) }}" class="btn">Leave a Review</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#6b7280;">
    If there's any issue with your order, you can raise a return or contact us within 7 days of delivery.
</p>
@endsection
