<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f9f1f5; color: #18181b; line-height: 1.6; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(196,21,92,.10); }

        /* Header */
        .header { background: #c4155c; padding: 32px 36px; text-align: center; }
        .header-brand { display: inline-block; border-bottom: 1px solid rgba(255,255,255,.3); padding-bottom: 12px; margin-bottom: 10px; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .header p { color: rgba(255,255,255,.8); font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; text-align: center; }

        /* Body */
        .body { padding: 36px 36px 28px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .lead { font-size: 15px; color: #374151; margin-bottom: 20px; line-height: 1.7; }

        /* Info Card */
        .card { background: #fdf2f8; border: 1px solid #f9a8d4; border-radius: 10px; padding: 4px 20px; margin: 20px 0; }
        .card-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #fce7f3; font-size: 14px; }
        .card-row:last-child { border-bottom: none; }
        .card-row .label { color: #9ca3af; }
        .card-row .value { font-weight: 600; color: #111827; text-align: right; }
        .card-row .value.total { color: #c4155c; font-size: 16px; }

        /* Neutral card (no color tint) */
        .card-neutral { background: #f9fafb; border-color: #e5e7eb; }
        .card-neutral .card-row { border-bottom-color: #f3f4f6; }

        /* Danger card */
        .card-danger { background: #fff1f2; border-color: #fecdd3; }
        .card-danger .card-row { border-bottom-color: #ffe4e6; }

        /* Warning notice */
        .notice { border-radius: 10px; padding: 14px 20px; margin: 16px 0; font-size: 13px; line-height: 1.6; }
        .notice-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
        .notice-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* Table */
        .table { width: 100%; border-collapse: collapse; font-size: 14px; margin: 4px 0; }
        .table th { background: #fdf2f8; text-align: left; padding: 10px 14px; font-weight: 700; color: #9d174d; border-bottom: 2px solid #f9a8d4; font-size: 12px; text-transform: uppercase; letter-spacing: .4px; }
        .table td { padding: 10px 14px; border-bottom: 1px solid #fce7f3; color: #374151; vertical-align: top; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-info { background: #dbeafe; color: #1d4ed8; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-pink { background: #fce7f3; color: #be185d; }

        /* CTA Button */
        .btn { display: inline-block; background: #c4155c; color: #ffffff !important; text-decoration: none; padding: 13px 32px; border-radius: 8px; font-weight: 700; font-size: 14px; letter-spacing: .3px; margin: 20px 0; }
        .btn-gray { background: #6b7280 !important; }
        .btn-danger { background: #dc2626 !important; }

        /* Misc */
        .divider { border: none; border-top: 1px solid #fce7f3; margin: 24px 0; }
        .section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #be185d; margin-bottom: 12px; }
        .help-text { font-size: 13px; color: #6b7280; line-height: 1.7; }

        /* Footer */
        .footer { background: #1a0a14; padding: 24px 36px; text-align: center; }
        .footer-links { margin-bottom: 10px; }
        .footer-links a { color: #f9a8d4; text-decoration: none; font-size: 13px; font-weight: 600; margin: 0 10px; }
        .footer p { font-size: 12px; color: #9ca3af; margin-top: 6px; }
        .footer .copyright { color: #6b7280; font-size: 11px; margin-top: 10px; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-brand">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <p style="text-align:center;">Premium Modest Fashion</p>
    </div>

    {{-- Body --}}
    <div class="body" style="padding: 36px 36px 28px;">
        @yield('content')
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-links">
            <a href="{{ url('/') }}">Visit Store</a>
            <a href="{{ url('/orders') }}">My Orders</a>
            <a href="{{ url('/contact') }}">Contact Us</a>
        </div>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>

</div>
</body>
</html>
