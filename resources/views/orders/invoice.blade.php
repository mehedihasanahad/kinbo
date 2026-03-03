<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #ffffff;
            line-height: 1.5;
        }

        .page { padding: 40px 48px; }

        /* ── Header ── */
        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .header table { width: 100%; border-collapse: collapse; }
        .header table td { vertical-align: top; }

        .brand-name { font-size: 22px; font-weight: bold; color: #059669; }
        .brand-tagline { font-size: 10px; color: #6b7280; margin-top: 3px; }

        .invoice-title { font-size: 26px; font-weight: bold; color: #d97706; text-align: right; }
        .invoice-meta { font-size: 10px; color: #6b7280; text-align: right; margin-top: 4px; }
        .invoice-meta strong { color: #1f2937; }

        /* ── Address boxes ── */
        .addr-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
        .addr-table td { width: 50%; vertical-align: top; padding: 14px 16px; }
        .addr-table .ship-box {
            background: #f0fdf4;
            border: 1px solid #a7f3d0;
            border-right: none;
            border-radius: 6px 0 0 6px;
        }
        .addr-table .pay-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: none;
            border-radius: 0 6px 6px 0;
        }

        .section-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #059669;
            margin-bottom: 7px;
        }
        .addr-name { font-size: 13px; font-weight: bold; color: #1f2937; margin-bottom: 2px; }
        .addr-line { font-size: 11px; color: #4b5563; margin-bottom: 1px; }

        /* ── Items table ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table thead tr { background: #059669; color: #ffffff; }
        .items-table thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table tbody tr { border-bottom: 1px solid #e5e7eb; }
        .items-table tbody tr.even { background: #f9fafb; }
        .items-table tbody td { padding: 9px 12px; font-size: 11px; vertical-align: middle; }
        .product-name { font-weight: 600; color: #111827; }
        .variant-label { font-size: 10px; color: #6b7280; }

        /* ── Totals ── */
        .totals-table {
            width: 260px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .totals-table td { padding: 4px 10px; font-size: 11px; }
        .totals-table .lbl { color: #6b7280; }
        .totals-table .amt { text-align: right; color: #1f2937; font-weight: 500; }
        .totals-table .disc-lbl { color: #059669; }
        .totals-table .disc-amt { text-align: right; color: #059669; font-weight: 500; }
        .totals-table .grand-row { border-top: 2px solid #059669; }
        .totals-table .grand-row td { padding-top: 8px; font-size: 14px; font-weight: bold; color: #059669; }

        .clearfix:after { content: ""; display: table; clear: both; }

        /* ── Notes ── */
        .notes-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 11px 14px;
            margin-bottom: 28px;
            font-size: 11px;
            color: #4b5563;
        }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
        .footer strong { color: #059669; }
    </style>
</head>
<body>
<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <table>
            <tr>
                <td style="width:50%;">
                    <div class="brand-name">{{ config('app.name') }}</div>
                    <div class="brand-tagline">Your trusted online store</div>
                </td>
                <td style="width:50%;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-meta">
                        <div><strong>#{{ $order->order_number }}</strong></div>
                        <div>Date: <strong>{{ $order->created_at->format('d M Y') }}</strong></div>
                        <div>Status: <strong>{{ ucfirst($order->status) }}</strong></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- SHIP TO / PAYMENT INFO --}}
    <table class="addr-table">
        <tr>
            <td class="ship-box">
                <div class="section-label">Ship To</div>
                <div class="addr-name">{{ $order->ship_name }}</div>
                <div class="addr-line">{{ $order->ship_phone }}</div>
                <div class="addr-line">{{ $order->ship_address }}</div>
                <div class="addr-line">
                    {{ $order->ship_city }}, {{ $order->ship_district }}
                    @if($order->ship_zip) &mdash; {{ $order->ship_zip }}@endif
                </div>
            </td>
            <td class="pay-box">
                <div class="section-label">Payment Info</div>
                <div class="addr-line">
                    <strong>Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                </div>
                <div class="addr-line">
                    <strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_status)) }}
                </div>
                @if($order->manualPayment?->transaction_id)
                    <div class="addr-line">
                        <strong>Transaction ID:</strong> {{ $order->manualPayment->transaction_id }}
                    </div>
                @endif
                @if($order->tracking_number)
                    <div class="addr-line">
                        <strong>Tracking #:</strong> {{ $order->tracking_number }}
                    </div>
                @endif
                @if($order->coupon)
                    <div class="addr-line">
                        <strong>Coupon:</strong> {{ $order->coupon->code }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    {{-- ORDER ITEMS --}}
    <div class="section-label" style="margin-bottom:10px;">Order Items</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:46%;">Product</th>
                <th style="width:16%; text-align:center;">Qty</th>
                <th style="width:19%; text-align:right;">Unit Price</th>
                <th style="width:19%; text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
                <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                    <td>
                        <div class="product-name">{{ $item->product_name }}</div>
                        @if($item->variant_label)
                            <div class="variant-label">{{ $item->variant_label }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;">{{ $item->quantity }}</td>
                    <td style="text-align:right;">BDT {{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align:right;">BDT {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="clearfix">
        <table class="totals-table">
            <tr>
                <td class="lbl">Subtotal</td>
                <td class="amt">BDT {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
                <tr>
                    <td class="lbl disc-lbl">Discount</td>
                    <td class="disc-amt">- BDT {{ number_format($order->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td class="lbl">Shipping</td>
                <td class="amt">{{ $order->shipping_amount > 0 ? 'BDT ' . number_format($order->shipping_amount, 2) : 'Free' }}</td>
            </tr>
            @if($order->tax_amount > 0)
                <tr>
                    <td class="lbl">Tax</td>
                    <td class="amt">BDT {{ number_format($order->tax_amount, 2) }}</td>
                </tr>
            @endif
            <tr class="grand-row">
                <td class="lbl">Total</td>
                <td class="amt" style="color:#059669;">BDT {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- NOTES --}}
    @if($order->notes)
        <div class="notes-box">
            <strong>Order Notes:</strong> {{ $order->notes }}
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <p>Thank you for shopping with <strong>{{ config('app.name') }}</strong>.</p>
        <p style="margin-top:4px;">Generated {{ now()->format('d M Y, h:i A') }}</p>
    </div>

</div>
</body>
</html>
