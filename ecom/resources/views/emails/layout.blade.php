<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f4f4f5; color: #18181b; line-height: 1.6; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #059669; padding: 28px 36px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -.3px; }
        .header p { color: rgba(255,255,255,.85); font-size: 13px; margin-top: 4px; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px 24px; margin: 20px 0; }
        .card-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .card-row:last-child { border-bottom: none; }
        .card-row .label { color: #6b7280; }
        .card-row .value { font-weight: 600; color: #111827; }
        .card-row .value.total { color: #059669; font-size: 16px; }
        .table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .table th { background: #f3f4f6; text-align: left; padding: 10px 12px; font-weight: 600; color: #374151; border-bottom: 2px solid #e5e7eb; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; color: #374151; vertical-align: top; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-info { background: #dbeafe; color: #1d4ed8; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .btn { display: inline-block; background: #059669; color: #ffffff !important; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; margin: 20px 0; }
        .btn:hover { background: #047857; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-bottom: 12px; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 36px; text-align: center; font-size: 12px; color: #9ca3af; }
        .footer a { color: #059669; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Your trusted online store in Bangladesh</p>
    </div>
    <div class="body">
        @yield('content')
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p style="margin-top:6px;">
            <a href="{{ url('/') }}">Visit Store</a> &nbsp;|&nbsp;
            <a href="{{ url('/orders') }}">My Orders</a>
        </p>
        <p style="margin-top:8px; font-size:11px; color:#d1d5db;">
            You received this email because you have an account or placed an order on {{ config('app.name') }}.
        </p>
    </div>
</div>
</body>
</html>
