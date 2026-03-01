<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function export(Request $request): StreamedResponse
    {
        $report    = $request->input('report', 'sales');
        $date_from = $request->input('date_from', now()->startOfMonth()->toDateString());
        $date_to   = $request->input('date_to',   now()->toDateString());
        $filename  = "report-{$report}-{$date_from}-to-{$date_to}.csv";

        $from = Carbon::parse($date_from)->startOfDay();
        $to   = Carbon::parse($date_to)->endOfDay();

        return response()->streamDownload(function () use ($report, $from, $to) {
            $handle = fopen('php://output', 'w');

            match ($report) {
                'sales'     => $this->writeSalesCsv($handle, $from, $to),
                'orders'    => $this->writeOrdersCsv($handle, $from, $to),
                'products'  => $this->writeProductsCsv($handle, $from, $to),
                'customers' => $this->writeCustomersCsv($handle, $from, $to),
                default     => null,
            };

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function writeSalesCsv($handle, Carbon $from, Carbon $to): void
    {
        fputcsv($handle, ['Date', 'Orders', 'Revenue (BDT)', 'Discounts (BDT)', 'Shipping (BDT)']);

        $rows = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue, SUM(discount_amount) as discounts, SUM(shipping_amount) as shipping')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($rows as $row) {
            fputcsv($handle, [$row->date, $row->orders, $row->revenue, $row->discounts, $row->shipping]);
        }

        fputcsv($handle, []);
        fputcsv($handle, ['TOTAL', $rows->sum('orders'), $rows->sum('revenue'), $rows->sum('discounts'), $rows->sum('shipping')]);
    }

    private function writeOrdersCsv($handle, Carbon $from, Carbon $to): void
    {
        fputcsv($handle, ['Status', 'Order Count', 'Total Amount (BDT)']);

        $rows = Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        foreach ($rows as $row) {
            fputcsv($handle, [ucfirst($row->status), $row->count, $row->total]);
        }
    }

    private function writeProductsCsv($handle, Carbon $from, Carbon $to): void
    {
        fputcsv($handle, ['Product', 'Units Sold', 'Revenue (BDT)']);

        $rows = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('order_items.product_name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        foreach ($rows as $row) {
            fputcsv($handle, [$row->product_name, $row->total_qty, $row->total_revenue]);
        }
    }

    private function writeCustomersCsv($handle, Carbon $from, Carbon $to): void
    {
        fputcsv($handle, ['Date', 'New Customers']);
        $total = 0;

        $rows = User::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($rows as $row) {
            fputcsv($handle, [$row->date, $row->count]);
            $total += $row->count;
        }

        fputcsv($handle, []);
        fputcsv($handle, ['TOTAL', $total]);
    }
}
