@php
    $pixelEnabled = \App\Models\Setting::get('facebook_pixel_enabled', '0');
    $pixelId      = \App\Models\Setting::get('facebook_pixel_id', '');

    $advancedMatch = [];

    if ($pixelEnabled && $pixelId && auth()->check()) {
        $user = auth()->user();

        // Email — lowercase trim, SHA-256
        if (!empty($user->email)) {
            $advancedMatch['em'] = hash('sha256', strtolower(trim($user->email)));
        }

        // Name — split first / last word
        if (!empty($user->name)) {
            $parts = explode(' ', trim($user->name), 2);
            $advancedMatch['fn'] = hash('sha256', strtolower($parts[0]));
            if (!empty($parts[1])) {
                $advancedMatch['ln'] = hash('sha256', strtolower($parts[1]));
            }
        }

        // Phone — strip non-digits, normalise to 880XXXXXXXXXX (Bangladesh E.164 without +)
        if (!empty($user->phone)) {
            $phone = preg_replace('/\D/', '', $user->phone);
            if (strlen($phone) >= 10) {
                if (str_starts_with($phone, '0')) {
                    $phone = '880' . substr($phone, 1);   // 01XXXXXXXXX → 8801XXXXXXXXX
                } elseif (!str_starts_with($phone, '880')) {
                    $phone = '880' . $phone;
                }
                $advancedMatch['ph'] = hash('sha256', $phone);
            }
        }

        // External ID — hashed user primary key for cross-device matching
        $advancedMatch['external_id'] = hash('sha256', (string) $user->id);

        // Country — Bangladesh
        $advancedMatch['country'] = hash('sha256', 'bd');

        // Address fields — load default address (single extra query, only when logged in)
        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        if ($defaultAddress) {
            if (!empty($defaultAddress->city)) {
                $advancedMatch['ct'] = hash('sha256', strtolower(trim($defaultAddress->city)));
            }
            if (!empty($defaultAddress->district)) {
                $advancedMatch['st'] = hash('sha256', strtolower(trim($defaultAddress->district)));
            }
            if (!empty($defaultAddress->zip_code)) {
                $advancedMatch['zp'] = hash('sha256', preg_replace('/\s+/', '', $defaultAddress->zip_code));
            }
        }
    }
@endphp
@if($pixelEnabled && $pixelId)
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $pixelId }}', @json($advancedMatch));
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"/></noscript>
@stack('pixel-events')
@endif
