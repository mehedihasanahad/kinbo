<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    use HasResourcePermissions;

    protected static string $viewPermission   = 'view_coupons';
    protected static string $createPermission = 'create_coupons';
    protected static string $editPermission   = 'edit_coupons';
    protected static string $deletePermission = 'delete_coupons';

    protected static ?string $model = Coupon::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Coupon Details')->schema([
                Forms\Components\TextInput::make('code')
                    ->required()->unique(ignoreRecord: true)->maxLength(50)
                    ->placeholder('e.g. SAVE20')
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('generate')
                            ->icon('heroicon-m-arrow-path')
                            ->action(fn ($set) => $set('code', strtoupper(\Str::random(8))))
                    ),

                Forms\Components\Select::make('type')
                    ->options(['percent' => 'Percentage (%)', 'fixed' => 'Fixed Amount (৳)'])
                    ->required()->default('percent')->live(),

                Forms\Components\TextInput::make('value')
                    ->numeric()->required()->minValue(0)
                    ->suffix(fn ($get) => $get('type') === 'percent' ? '%' : '৳'),

                Forms\Components\TextInput::make('max_discount_amount')
                    ->numeric()->nullable()->prefix('৳')
                    ->label('Max Discount Cap')
                    ->visible(fn ($get) => $get('type') === 'percent'),

                Forms\Components\TextInput::make('min_order_amount')
                    ->numeric()->nullable()->prefix('৳')->label('Minimum Order'),

                Forms\Components\TextInput::make('max_uses')
                    ->numeric()->integer()->nullable()->label('Total Usage Limit'),

                Forms\Components\TextInput::make('per_user_limit')
                    ->numeric()->integer()->default(1)->label('Per User Limit'),
            ])->columns(3),

            Forms\Components\Section::make('Validity')->schema([
                Forms\Components\DateTimePicker::make('starts_at')->nullable()->label('Start Date'),
                Forms\Components\DateTimePicker::make('expires_at')->nullable()->label('Expiry Date'),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(3),

            Forms\Components\Section::make('Restrictions (optional)')->schema([
                Forms\Components\Select::make('product_ids')
                    ->label('Limit to Products')
                    ->multiple()->searchable()
                    ->options(fn () => Product::active()->with('translations')
                        ->get()->mapWithKeys(fn ($p) => [$p->id => $p->getTranslation('en')?->name ?? $p->sku]))
                    ->nullable(),

                Forms\Components\Select::make('category_ids')
                    ->label('Limit to Categories')
                    ->multiple()->searchable()
                    ->options(fn () => Category::active()->with('translations')
                        ->get()->mapWithKeys(fn ($c) => [$c->id => $c->getTranslation('en')?->name ?? '#' . $c->id]))
                    ->nullable(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()->sortable()->copyable()->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors(['primary' => 'percent', 'warning' => 'fixed']),

                Tables\Columns\TextColumn::make('value')
                    ->getStateUsing(fn ($record) =>
                        $record->type === 'percent'
                            ? $record->value . '%'
                            : '৳' . number_format($record->value, 0)),

                Tables\Columns\TextColumn::make('min_order_amount')
                    ->money('BDT')->label('Min Order')->placeholder('—'),

                Tables\Columns\TextColumn::make('used_count')->label('Used'),

                Tables\Columns\TextColumn::make('max_uses')->label('Limit')->placeholder('∞'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()->label('Expires')->placeholder('Never')
                    ->color(fn ($record) => $record->expires_at?->isPast() ? 'danger' : null),

                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('type')
                    ->options(['percent' => 'Percentage', 'fixed' => 'Fixed']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
