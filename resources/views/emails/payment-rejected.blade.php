@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p class="lead">We're sorry, but we were unable to verify your payment for order <strong>{{ $order->order_number }}</strong>.</p>

<div class="card card-danger">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Payment Method</span>
        <span class="value">{{ strtoupper($payment->method) }}</span>
    </div>
    <div class="card-row">
        <span class="label">Status</span>
        <span class="value"><span class="badge badge-danger">Rejected</span></span>
    </div>
    @if($payment->rejection_reason)
    <div class="card-row">
        <span class="label">Reason</span>
        <span class="value" style="color:#dc2626;">{{ $payment->rejection_reason }}</span>
    </div>
    @endif
</div>

<p style="font-size:14px;color:#374151;margin-top:20px;line-height:1.7;">
    Please contact our support team if you believe this is a mistake, or try placing a new order. We apologize for any inconvenience.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ url('/') }}" class="btn btn-danger">Contact Support</a>
</div>

<hr class="divider">
<p class="help-text">If you have any questions, please reach out to us. We're here to help.</p>
@endsection
