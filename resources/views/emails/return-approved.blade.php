@extends('emails.layout')

@section('content')
<p class="greeting">Hi {{ $order->user->name }},</p>
<p style="font-size:15px;color:#374151;margin-bottom:20px;">
    Great news! Your return request for order <strong>{{ $order->order_number }}</strong> has been <strong style="color:#059669;">approved</strong>.
</p>

<div class="card" style="background:#f0fdf4;border-color:#bbf7d0;">
    <div class="card-row">
        <span class="label">Order Number</span>
        <span class="value">{{ $order->order_number }}</span>
    </div>
    <div class="card-row">
        <span class="label">Return Status</span>
        <span class="value"><span class="badge badge-success">Approved</span></span>
    </div>
    <div class="card-row">
        <span class="label">Order Total</span>
        <span class="value">৳{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

@if($order->isPaid())
@php $refundDays = \App\Models\Setting::get('refund_days', '5'); @endphp
<div class="card" style="background:#fffbeb;border-color:#fcd34d;margin-top:16px;">
    <p style="font-size:13px;color:#92400e;">
        <strong>Refund Notice:</strong> Your refund of <strong>৳{{ number_format($order->total_amount, 2) }}</strong> will be initiated within {{ $refundDays }} business days to your original payment method. Please contact us if you have any questions.
    </p>
</div>
@endif

@if($order->returnRequest?->admin_notes)
<div class="card" style="margin-top:16px;">
    <p style="font-size:13px;color:#374151;"><strong>Note from our team:</strong></p>
    <p style="font-size:13px;color:#6b7280;margin-top:6px;">{{ $order->returnRequest->admin_notes }}</p>
</div>
@endif

<p style="font-size:14px;color:#374151;margin-top:20px;">
    Please ship the items back using the original packaging if possible. Our team will follow up with return shipping instructions if needed.
</p>

<div style="text-align:center;margin-top:24px;">
    <a href="{{ route('orders.show', $order) }}" class="btn">View Order</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#6b7280;">Thank you for shopping with {{ config('app.name') }}. We're sorry for any inconvenience.</p>
@endsection
