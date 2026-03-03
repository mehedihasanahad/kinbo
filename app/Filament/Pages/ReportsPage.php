<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class ReportsPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Reports & Analytics';
    protected static ?string $title           = 'Reports & Analytics';
    protected static ?int    $navigationSort  = 5;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasPermission('view_reports'));
    }

    protected static string $view = 'filament.pages.reports-page';

    // Filter state — bound via statePath('filters')
    public array $filters = [
        'date_from' => '',
        'date_to'   => '',
        'report'    => 'sales',
    ];

    public function mount(): void
    {
        $this->filters = [
            'date_from' => now()->startOfMonth()->toDateString(),
            'date_to'   => now()->toDateString(),
            'report'    => 'sales',
        ];

        $this->filtersForm->fill($this->filters);
    }

    protected function getForms(): array
    {
        return ['filtersForm'];
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date_from')
                ->label('From')
                ->required()
                ->maxDate(now())
                ->native(false),

            Forms\Components\DatePicker::make('date_to')
                ->label('To')
                ->required()
                ->maxDate(now())
                ->native(false),

            Forms\Components\Select::make('report')
                ->label('Report Type')
                ->options([
                    'sales'     => 'Sales Revenue',
                    'orders'    => 'Orders by Status',
                    'products'  => 'Top Products',
                    'customers' => 'New Customers',
                ])
                ->required(),
        ])->statePath('filters')->columns(4);
    }

    public function applyFilters(): void
    {
        $data = $this->filtersForm->getState();
        $this->filters = array_merge($this->filters, $data);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function dateRange(): array
    {
        return [
            Carbon::parse($this->filters['date_from'] ?: now()->startOfMonth())->startOfDay(),
            Carbon::parse($this->filters['date_to']   ?: now())->endOfDay(),
        ];
    }

    // ── Report data methods ──────────────────────────────────────────────────

    public function getSalesData(): array
    {
        [$from, $to] = $this->dateRange();

        $rows = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue, SUM(discount_amount) as discounts, SUM(shipping_amount) as shipping')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'rows'   => $rows,
            'totals' => [
                'orders'    => $rows->sum('orders'),
                'revenue'   => $rows->sum('revenue'),
                'discounts' => $rows->sum('discounts'),
                'shipping'  => $rows->sum('shipping'),
            ],
        ];
    }

    public function getOrdersData(): array
    {
        [$from, $to] = $this->dateRange();

        return Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }

    public function getProductsData(): array
    {
        [$from, $to] = $this->dateRange();

        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('order_items.product_id, order_items.product_name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function getCustomersData(): array
    {
        [$from, $to] = $this->dateRange();

        return User::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    public function getCsvUrl(): string
    {
        return route('admin.reports.export', [
            'report'    => $this->filters['report'],
            'date_from' => $this->filters['date_from'],
            'date_to'   => $this->filters['date_to'],
        ]);
    }
}
