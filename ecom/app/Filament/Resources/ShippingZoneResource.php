<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingZoneResource\Pages;
use App\Models\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Shipping Zones';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Zone Info')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(191),
                Forms\Components\Textarea::make('description')->rows(2)->nullable(),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(3),

            Forms\Components\Section::make('Districts in this Zone')
                ->description('Enter one district name per entry.')
                ->schema([
                    Forms\Components\Repeater::make('districts')
                        ->relationship('districts')
                        ->schema([
                            Forms\Components\TextInput::make('district_name')
                                ->label('District')->required()->maxLength(100),
                        ])
                        ->columns(1)
                        ->addActionLabel('Add District')
                        ->collapsible()
                        ->defaultItems(0),
                ]),

            Forms\Components\Section::make('Shipping Rates')
                ->schema([
                    Forms\Components\Repeater::make('rates')
                        ->relationship('rates')
                        ->schema([
                            Forms\Components\TextInput::make('method_name')
                                ->label('Method Name')->required()->placeholder('e.g. Standard Delivery'),
                            Forms\Components\TextInput::make('cost')
                                ->numeric()->prefix('৳')->required(),
                            Forms\Components\TextInput::make('free_shipping_above')
                                ->numeric()->prefix('৳')->nullable()->label('Free Above (৳)'),
                            Forms\Components\TextInput::make('estimated_days_min')
                                ->numeric()->integer()->nullable()->label('Min Days'),
                            Forms\Components\TextInput::make('estimated_days_max')
                                ->numeric()->integer()->nullable()->label('Max Days'),
                            Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
                        ])
                        ->columns(3)
                        ->addActionLabel('Add Rate')
                        ->collapsible()
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(50)->placeholder('—'),
                Tables\Columns\TextColumn::make('districts_count')
                    ->label('Districts')->counts('districts'),
                Tables\Columns\TextColumn::make('rates_count')
                    ->label('Rates')->counts('rates'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListShippingZones::route('/'),
            'create' => Pages\CreateShippingZone::route('/create'),
            'edit'   => Pages\EditShippingZone::route('/{record}/edit'),
        ];
    }
}
