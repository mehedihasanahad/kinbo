<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PathaoDistrictMappingResource\Pages;
use App\Models\PathaoDistrictMapping;
use App\Models\ShippingZoneDistrict;
use App\Services\Couriers\PathaoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;

class PathaoDistrictMappingResource extends Resource
{
    protected static ?string $model = PathaoDistrictMapping::class;
    protected static ?string $navigationIcon  = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Courier';
    protected static ?string $navigationLabel = 'Pathao District Mappings';
    protected static ?int    $navigationSort  = 12;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasPermission('manage_courier_settings'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('District → Pathao Mapping')
                ->schema([
                    Forms\Components\Select::make('district_name')
                        ->label('District')
                        ->options(
                            ShippingZoneDistrict::orderBy('district_name', 'asc')
                                ->pluck('district_name', 'district_name')
                        )
                        ->searchable()
                        ->required()
                        ->unique(
                            table: 'pathao_district_mappings',
                            column: 'district_name',
                            ignoreRecord: true,
                        ),

                    Forms\Components\TextInput::make('pathao_city_id')
                        ->label('City ID')
                        ->numeric()
                        ->integer()
                        ->required()
                        ->minValue(1),

                    Forms\Components\TextInput::make('pathao_zone_id')
                        ->label('Zone ID')
                        ->numeric()
                        ->integer()
                        ->required()
                        ->minValue(1),

                    Forms\Components\TextInput::make('pathao_area_id')
                        ->label('Area ID')
                        ->numeric()
                        ->integer()
                        ->nullable()
                        ->minValue(1)
                        ->helperText('Optional. Leave blank if not required.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('district_name')
                    ->label('District')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pathao_city_id')
                    ->label('City ID')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('pathao_zone_id')
                    ->label('Zone ID')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('pathao_area_id')
                    ->label('Area ID')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable(),
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
            ->defaultSort('district_name', 'asc');
    }

    public static function getHeaderActions(): array
    {
        return [
            Action::make('city_reference')
                ->label('City Reference')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->form(function (): array {
                    try {
                        $service = app(PathaoService::class);

                        if (! $service->isConfigured()) {
                            return [
                                Forms\Components\Placeholder::make('notice')
                                    ->label('')
                                    ->content(new HtmlString(
                                        '<p class="text-warning-600 font-medium">Pathao credentials are not configured. Save them in Courier Settings first.</p>'
                                    )),
                            ];
                        }

                        $cities = $service->fetchCities();

                        $rows = collect($cities)->map(function ($city) {
                            $id   = htmlspecialchars((string) ($city['city_id']   ?? $city['id']   ?? ''));
                            $name = htmlspecialchars((string) ($city['city_name'] ?? $city['name'] ?? ''));
                            return "<tr><td class=\"pr-6 py-1 font-mono text-sm\">{$id}</td><td class=\"py-1 text-sm\">{$name}</td></tr>";
                        })->implode('');

                        $html = <<<HTML
                        <table class="w-full border-collapse">
                          <thead>
                            <tr class="border-b">
                              <th class="text-left pr-6 py-2 text-xs uppercase tracking-wide text-gray-500">City ID</th>
                              <th class="text-left py-2 text-xs uppercase tracking-wide text-gray-500">City Name</th>
                            </tr>
                          </thead>
                          <tbody>{$rows}</tbody>
                        </table>
                        HTML;

                        return [
                            Forms\Components\Placeholder::make('cities_table')
                                ->label('')
                                ->content(new HtmlString($html)),
                        ];
                    } catch (\Throwable $e) {
                        return [
                            Forms\Components\Placeholder::make('error')
                                ->label('')
                                ->content(new HtmlString(
                                    '<p class="text-danger-600 font-medium">Failed to fetch cities: ' . htmlspecialchars($e->getMessage()) . '</p>'
                                )),
                        ];
                    }
                })
                ->modalHeading('Pathao City Reference')
                ->modalSubmitActionLabel('Close')
                ->modalCancelAction(false),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPathaoDistrictMappings::route('/'),
            'create' => Pages\CreatePathaoDistrictMapping::route('/create'),
            'edit'   => Pages\EditPathaoDistrictMapping::route('/{record}/edit'),
        ];
    }
}
