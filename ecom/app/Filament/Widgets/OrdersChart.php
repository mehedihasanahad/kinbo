<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders — Last 30 Days';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $counts = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::today()->subDays(29))
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label'           => 'Orders',
                    'data'            => $days->map(fn ($d) => $counts[$d->toDateString()] ?? 0)->values()->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',
                    'borderColor'     => 'rgb(99, 102, 241)',
                    'fill'            => true,
                    'tension'         => 0.4,
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
