<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CourierOrderResource\Pages;
use App\Models\CourierOrder;
use App\Services\CourierManager;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourierOrderResource extends Resource
{
    protected static ?string $model          = CourierOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Courier';
    protected static ?string $navigationLabel = 'Courier Orders';
    protected static ?int    $navigationSort  = 11;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasPermission('view_courier_orders'));
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable()
                    ->url(fn (CourierOrder $record) => route('filament.admin.resources.orders.edit', $record->order_id))
                    ->color('primary'),

                Tables\Columns\TextColumn::make('courier')
                    ->label('Provider')
                    ->formatStateUsing(fn (string $state) => CourierOrder::courierLabel($state))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('consignment_id')
                    ->label('Consignment ID')
                    ->copyable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Tracking')
                    ->copyable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => CourierOrder::statusColor($state)),

                Tables\Columns\TextColumn::make('cod_amount')
                    ->label('COD')
                    ->money('BDT')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.ship_name')
                    ->label('Recipient')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dispatched_at')
                    ->label('Dispatched')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->placeholder('Not yet'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('courier')
                    ->options(['steadfast' => 'Steadfast', 'pathao' => 'Pathao']),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'          => 'Pending',
                        'in_review'        => 'In Review',
                        'hold'             => 'Hold',
                        'delivered'        => 'Delivered',
                        'partial_delivered' => 'Partial Delivered',
                        'cancelled'        => 'Cancelled',
                        'failed'           => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sync_status')
                    ->label('Sync Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->visible(fn (CourierOrder $record) => $record->isDispatched())
                    ->action(function (CourierOrder $record): void {
                        try {
                            $manager  = app(CourierManager::class);
                            $service  = $manager->driver($record->courier);
                            $response = $service->getStatusByConsignmentId($record->consignment_id);

                            $newStatus = $response['delivery_status'] ?? null;

                            if ($newStatus) {
                                $record->update(['status' => $newStatus]);

                                Notification::make()
                                    ->title('Status updated')
                                    ->body("Courier status: {$newStatus}")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('No status returned')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Sync failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulk_sync_status')
                    ->label('Sync Status')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function ($records): void {
                        $manager = app(CourierManager::class);
                        $updated = 0;

                        foreach ($records as $record) {
                            if (! $record->isDispatched()) {
                                continue;
                            }

                            try {
                                $service  = $manager->driver($record->courier);
                                $response = $service->getStatusByConsignmentId($record->consignment_id);
                                $newStatus = $response['delivery_status'] ?? null;

                                if ($newStatus) {
                                    $record->update(['status' => $newStatus]);
                                    $updated++;
                                }
                            } catch (\Throwable) {
                                // skip individual failures in bulk
                            }
                        }

                        Notification::make()
                            ->title("{$updated} order(s) synced.")
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourierOrders::route('/'),
        ];
    }
}
