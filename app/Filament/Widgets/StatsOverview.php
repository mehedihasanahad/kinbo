<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue   = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders  = Order::where('status', 'pending')->count();
        $totalOrders    = Order::count();
        $totalProducts  = Product::active()->count();
        $totalUsers     = User::where('is_active', true)->count();
        $lowStock       = Product::active()->where('stock', '>', 0)
                            ->whereColumn('stock', '<=', 'low_stock_threshold')->count();

        // Month-over-month revenue comparison
        $thisMonth    = Order::where('payment_status', 'paid')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('total_amount');
        $lastMonth    = Order::where('payment_status', 'paid')
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->whereYear('created_at', now()->subMonth()->year)
                            ->sum('total_amount');
        $revenueDesc  = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) . '% vs last month'
            : '৳' . number_format($thisMonth, 0) . ' this month';

        // Average order value
        $paidCount = Order::where('payment_status', 'paid')->count();
        $aov       = $paidCount > 0 ? $totalRevenue / $paidCount : 0;

        // New customers this month
        $newCustomers = User::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();

        // Today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Revenue', '৳' . number_format($totalRevenue, 0))
                ->description($revenueDesc)
                ->descriptionIcon($thisMonth >= $lastMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($thisMonth >= $lastMonth ? 'success' : 'danger'),

            Stat::make('Total Orders', number_format($totalOrders))
                ->description($todayOrders . ' today · ' . $pendingOrders . ' pending')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Avg. Order Value', '৳' . number_format($aov, 0))
                ->description('Per paid order')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make('Active Products', number_format($totalProducts))
                ->description($lowStock . ' low stock')
                ->descriptionIcon('heroicon-m-cube')
                ->color($lowStock > 0 ? 'danger' : 'primary'),

            Stat::make('Customers', number_format($totalUsers))
                ->description($newCustomers . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

        ];
    }
}
