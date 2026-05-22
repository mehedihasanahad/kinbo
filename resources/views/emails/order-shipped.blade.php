@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p class="lead">Your order is on its way! Here are the shipping details for order <strong>{{ $order->order_number }}</strong>.</p>

<div class="card">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Status</span>
        <span class="value"><span class="badge badge-info">Shipped</span></span>
    </div>
    @if($order->tracking_number)
    <div class="card-row">
        <span class="label">Tracking Number</span>
        <span class="value" style="font-family:monospace;letter-spacing:.5px;">{{ $order->tracking_number }}</span>
    </div>
    @endif
    <div class="card-row">
        <span class="label">Shipping To</span>
        <span class="value">{{ $order->ship_district }}, {{ $order->ship_city }}</span>
    </div>
</div>

<p class="section-title" style="margin-top:24px;">Items Shipped</p>
<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th style="text-align:right">Qty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product_name }}
                @if($item->variant_label)
                    <br><span style="font-size:12px;color:#9ca3af;">{{ $item->variant_label }}</span>
                @endif
            </td>
            <td style="text-align:right">{{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ route('orders.show', $order) }}" class="btn">Track My Order</a>
</div>

<hr class="divider">
<p class="help-text">Estimated delivery: please allow a few business days depending on your location. Thank you for your patience!</p>
@endsection
