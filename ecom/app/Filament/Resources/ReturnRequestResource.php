<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnRequestResource\Pages;
use App\Mail\ReturnApproved;
use App\Mail\ReturnRejected;
use App\Models\Order;
use App\Models\ReturnRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class ReturnRequestResource extends Resource
{
    protected static ?string $model = ReturnRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Return Requests';

    public static function getNavigationBadge(): ?string
    {
        return (string) ReturnRequest::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Return Request Details')->schema([
                Forms\Components\Placeholder::make('order_number')
                    ->label('Order')
                    ->content(fn (ReturnRequest $record) => $record->order->order_number),

                Forms\Components\Placeholder::make('customer')
                    ->label('Customer')
                    ->content(fn (ReturnRequest $record) => $record->user->name . ' (' . $record->user->email . ')'),

                Forms\Components\Placeholder::make('reason')
                    ->label('Return Reason')
                    ->content(fn (ReturnRequest $record) => $record->reason),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('admin_notes')
                    ->label('Admin Notes (sent to customer)')
                    ->rows(3)
                    ->nullable(),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason')
                    ->limit(60)
                    ->tooltip(fn (ReturnRequest $record) => $record->reason),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notes for customer (optional)')
                            ->rows(3),
                    ])
                    ->visible(fn (ReturnRequest $record) => $record->status === 'pending')
                    ->action(function (ReturnRequest $record, array $data) {
                        $record->update([
                            'status'      => ReturnRequest::STATUS_APPROVED,
                            'admin_notes' => $data['admin_notes'] ?? null,
                        ]);

                        $order = $record->order;
                        $order->update([
                            'status'         => Order::STATUS_RETURNED,
                            'payment_status' => $order->isPaid() ? Order::PAYMENT_REFUNDED : $order->payment_status,
                        ]);

                        Mail::to($record->user->email)->send(new ReturnApproved($order->fresh(['returnRequest'])));

                        Notification::make()->title('Return approved and customer notified.')->success()->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Reason for rejection (sent to customer)')
                            ->required()
                            ->rows(3),
                    ])
                    ->visible(fn (ReturnRequest $record) => $record->status === 'pending')
                    ->action(function (ReturnRequest $record, array $data) {
                        $record->update([
                            'status'      => ReturnRequest::STATUS_REJECTED,
                            'admin_notes' => $data['admin_notes'],
                        ]);

                        Mail::to($record->user->email)->send(new ReturnRejected($record->order->fresh(['returnRequest'])));

                        Notification::make()->title('Return rejected and customer notified.')->danger()->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturnRequests::route('/'),
            'view'  => Pages\ViewReturnRequest::route('/{record}'),
            'edit'  => Pages\EditReturnRequest::route('/{record}/edit'),
        ];
    }
}
