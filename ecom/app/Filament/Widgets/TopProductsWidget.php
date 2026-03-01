<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top 10 Products (All Time)';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function getTableRecordKey(\Illuminate\Database\Eloquent\Model $record): string
    {
        return (string) $record->product_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue, COUNT(DISTINCT order_id) as order_count')
                    ->groupBy('product_id', 'product_name')
                    ->orderByDesc('total_revenue')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('order_count')
                    ->label('Orders')
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Units Sold')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->formatStateUsing(fn ($state) => '৳' . number_format($state, 0))
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
