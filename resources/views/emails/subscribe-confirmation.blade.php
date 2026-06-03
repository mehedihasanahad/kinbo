@extends('emails.layout')

@section('content')
<div style="padding: 16px 24px;">
    <p class="greeting">Thank you for subscribing!</p>
    <p class="lead">
        Welcome to <strong>{{ $appName }}</strong>! Please confirm your email address to start receiving our latest deals, new arrivals, and exclusive offers.
    </p>

    <div style="text-align:center;margin:32px 0;">
        <a href="{{ $confirmUrl }}" class="btn">Confirm Subscription</a>
    </div>

    <div class="notice notice-info">
        This link will expire in <strong>48 hours</strong>. If you did not subscribe, you can safely ignore this email.
    </div>

    <hr class="divider">
    <p class="help-text">
        Don't want to receive emails from us?
        <a href="{{ $unsubscribeUrl }}" style="color:#FD6C01;text-decoration:none;font-weight:600;">Unsubscribe here</a>.
    </p>
</div>
@endsection
