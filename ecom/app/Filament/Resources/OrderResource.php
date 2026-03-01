<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Status')->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                        'returned'   => 'Returned',
                    ])->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid'                 => 'Unpaid',
                        'pending_verification'   => 'Pending Verification',
                        'paid'                   => 'Paid',
                        'refunded'               => 'Refunded',
                        'failed'                 => 'Failed',
                    ])->required(),

                Forms\Components\TextInput::make('tracking_number')->maxLength(100)->nullable(),
            ])->columns(3),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Order Details')->schema([
                Infolists\Components\TextEntry::make('order_number')->label('Order #')->copyable(),
                Infolists\Components\TextEntry::make('status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled', 'returned' => 'danger',
                        default      => 'gray',
                    }),
                Infolists\Components\TextEntry::make('payment_status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'      => 'success',
                        'unpaid'    => 'danger',
                        'refunded'  => 'info',
                        default     => 'warning',
                    }),
                Infolists\Components\TextEntry::make('payment_method'),
                Infolists\Components\TextEntry::make('total_amount')->money('BDT'),
                Infolists\Components\TextEntry::make('created_at')->dateTime(),
            ])->columns(3),

            Infolists\Components\Section::make('Customer')->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Name'),
                Infolists\Components\TextEntry::make('user.email')->label('Email'),
            ])->columns(2),

            Infolists\Components\Section::make('Shipping Address')->schema([
                Infolists\Components\TextEntry::make('ship_name'),
                Infolists\Components\TextEntry::make('ship_phone'),
                Infolists\Components\TextEntry::make('ship_address'),
                Infolists\Components\TextEntry::make('ship_city'),
                Infolists\Components\TextEntry::make('ship_district'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()->sortable()->copyable()->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('BDT')->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger'  => ['cancelled', 'returned'],
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'success' => 'paid',
                        'danger'  => ['unpaid', 'failed'],
                        'warning' => 'pending_verification',
                        'info'    => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('payment_method'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                        'returned'   => 'Returned',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid'               => 'Unpaid',
                        'pending_verification' => 'Pending Verification',
                        'paid'                 => 'Paid',
                        'refunded'             => 'Refunded',
                        'failed'               => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('warning')
                    ->action(function (Order $record) {
                        $record->load(['items', 'manualPayment', 'coupon']);
                        $pdf = Pdf::loadView('orders.invoice', ['order' => $record])->setPaper('a4', 'portrait');
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Invoice-' . $record->order_number . '.pdf',
                            ['Content-Type' => 'application/pdf']
                        );
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'view'   => Pages\ViewOrder::route('/{record}'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
