<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Hero Banners';
    protected static ?string $modelLabel      = 'Banner';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Banner Content')
                ->description('Text content displayed over the banner image.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(191)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('subtitle')
                        ->maxLength(255)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('button_text')
                        ->label('Button Label')
                        ->maxLength(80)
                        ->nullable(),

                    Forms\Components\TextInput::make('button_url')
                        ->label('Button URL')
                        ->url()
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Images')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Desktop Banner Image')
                        ->image()
                        ->required()
                        ->directory('banners')
                        ->imagePreviewHeight('160')
                        ->maxSize(4096)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->helperText('Recommended: 1920×700px. JPEG/PNG/WebP, max 4MB.'),

                    Forms\Components\FileUpload::make('mobile_image')
                        ->label('Mobile Banner Image')
                        ->image()
                        ->nullable()
                        ->directory('banners')
                        ->imagePreviewHeight('160')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->helperText('Recommended: 768×500px. Falls back to desktop image if not set.'),
                ])->columns(2),

            Forms\Components\Section::make('Visibility & Scheduling')
                ->schema([
                    Forms\Components\Select::make('locale')
                        ->options([
                            'all' => 'All Languages',
                            'en'  => 'English Only',
                            'bn'  => 'Bengali Only',
                        ])
                        ->default('all')
                        ->required(),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Lower number = shown first.'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->inline(false),

                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('Starts At')
                        ->nullable()
                        ->helperText('Leave blank to show immediately.'),

                    Forms\Components\DateTimePicker::make('ends_at')
                        ->label('Ends At')
                        ->nullable()
                        ->helperText('Leave blank to show indefinitely.'),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Preview')
                    ->height(60)
                    ->width(120)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover']),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('locale')
                    ->colors([
                        'primary' => 'all',
                        'success' => 'en',
                        'warning' => 'bn',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'all' => 'All',
                        'en'  => 'English',
                        'bn'  => 'Bengali',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\SelectFilter::make('locale')
                    ->options(['all' => 'All', 'en' => 'English', 'bn' => 'Bengali']),
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
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
