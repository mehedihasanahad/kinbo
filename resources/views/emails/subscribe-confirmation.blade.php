<x-mail::message>
# Confirm Your Subscription

Thank you for subscribing to **{{ $appName }}** newsletters!

Please click the button below to confirm your email address and start receiving our latest deals, new arrivals, and exclusive offers.

<x-mail::button :url="$confirmUrl" color="primary">
Confirm Subscription
</x-mail::button>

This link will expire in **48 hours**. If you did not subscribe, you can safely ignore this email.

---

<small>Don't want to receive emails from us? <a href="{{ $unsubscribeUrl }}">Unsubscribe here</a>.</small>

Thanks,
**{{ $appName }} Team**
</x-mail::message>
