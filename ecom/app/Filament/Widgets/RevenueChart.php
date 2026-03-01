<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue — Last 30 Days';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $revenue = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label'           => 'Revenue (৳)',
                    'data'            => $days->map(fn ($d) => (float) ($revenue[$d->toDateString()] ?? 0))->values()->toArray(),
                    'backgroundColor' => 'rgba(5, 150, 105, 0.12)',
                    'borderColor'     => 'rgb(5, 150, 105)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'pointRadius'     => 2,
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('M d'))->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
