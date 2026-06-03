@extends('emails.layout')

@section('content')
{!! $body !!}

<hr style="border:none;border-top:1px solid #ffe8d1;margin:24px 0;">
<p style="font-size:13px;color:#6b7280;line-height:1.7;">
    You're receiving this because you subscribed to {{ $appName }} newsletters.<br>
    <a href="{{ $unsubscribeUrl }}" style="color:#FD6C01;text-decoration:none;font-weight:600;">Unsubscribe</a> at any time.
</p>
@endsection
