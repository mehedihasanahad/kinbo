<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Orders by Status';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
        $colors   = [
            'pending'    => 'rgba(245, 158, 11, 0.8)',
            'processing' => 'rgba(59, 130, 246, 0.8)',
            'shipped'    => 'rgba(99, 102, 241, 0.8)',
            'delivered'  => 'rgba(5, 150, 105, 0.8)',
            'cancelled'  => 'rgba(239, 68, 68, 0.8)',
            'returned'   => 'rgba(107, 114, 128, 0.8)',
        ];

        $counts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $data   = collect($statuses)->map(fn ($s) => (int) ($counts[$s] ?? 0))->values()->toArray();
        $labels = collect($statuses)->map(fn ($s) => ucfirst($s))->values()->toArray();
        $bgs    = collect($statuses)->map(fn ($s) => $colors[$s])->values()->toArray();

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => $bgs,
                    'borderWidth'     => 2,
                    'borderColor'     => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
