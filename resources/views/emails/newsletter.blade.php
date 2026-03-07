<x-mail::message>
{!! $body !!}

---

<small>You're receiving this because you subscribed to {{ $appName }} newsletters.<br>
<a href="{{ $unsubscribeUrl }}">Unsubscribe</a> at any time.</small>
</x-mail::message>
