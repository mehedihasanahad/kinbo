<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $totalProducts = Product::active()->count();
        $totalUsers = User::where('is_active', true)->count();
        $lowStock = Product::active()->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'low_stock_threshold')->count();

        return [
            Stat::make('Total Revenue', '৳' . number_format($totalRevenue, 2))
                ->description('From paid orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Orders', number_format($totalOrders))
                ->description($pendingOrders . ' pending')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Active Products', number_format($totalProducts))
                ->description($lowStock . ' low stock')
                ->descriptionIcon('heroicon-m-cube')
                ->color($lowStock > 0 ? 'danger' : 'primary'),

            Stat::make('Customers', number_format($totalUsers))
                ->description('Active accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
