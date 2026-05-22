@extends('emails.layout')

@section('content')
{!! $body !!}

<hr class="divider">
<p class="help-text">
    You're receiving this because you subscribed to {{ $appName }} newsletters.<br>
    <a href="{{ $unsubscribeUrl }}" style="color:#c4155c;text-decoration:none;font-weight:600;">Unsubscribe</a> at any time.
</p>
@endsection
