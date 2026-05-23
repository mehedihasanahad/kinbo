<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    use HasResourcePermissions;

    protected static string $viewPermission   = 'view_content';
    protected static string $createPermission = 'create_content';
    protected static string $editPermission   = 'edit_content';
    protected static string $deletePermission = 'delete_content';

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

            Forms\Components\Section::make('Banner Image')
                ->description('Upload a high-resolution image (minimum 1920 × 700 px). It will be auto-cropped to exactly 1920 × 700 px and displayed on all screen sizes.')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Banner Image')
                        ->image()
                        ->required()
                        ->directory('banners')
                        ->disk('public')
                        ->visibility('public')
                        ->imagePreviewHeight('200')
                        ->maxSize(4096)
                        ->minSize(10)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('700')
                        ->imageResizeMode('cover')
                        ->imageResizeUpscale(false)
                        ->helperText('📐 Required: 1920 × 700 px minimum | Aspect ratio 2.74:1 | JPEG / PNG / WebP | Max 4 MB — auto-cropped to 1920 × 700 px on upload.')
                        ->rules([
                            'required',
                            'image',
                            'mimes:jpeg,jpg,png,webp',
                            'max:4096',
                            'dimensions:min_width=1920,min_height=700,max_width=8000,max_height=4000',
                        ])
                        ->validationMessages([
                            'required'   => 'A banner image is required.',
                            'image'      => 'The file must be a valid image.',
                            'mimes'      => 'Only JPEG, PNG, and WebP images are accepted.',
                            'max'        => 'The image must not exceed 4 MB.',
                            'dimensions' => 'Image must be at least 1920 × 700 px. Please upload a higher-resolution image.',
                        ])
                        ->columnSpanFull(),
                ])
                ->collapsible(false),

            Forms\Components\Section::make('Visibility & Scheduling')
                ->schema([
                    Forms\Components\Select::make('locale')
                        ->options([
                            'all' => 'All Visitors',
                            'en'  => 'English Only',
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

                Tables\Columns\TextColumn::make('locale')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'all' => 'primary',
                        'en'  => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'all' => 'All',
                        'en'  => 'English',
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
                    ->options(['all' => 'All', 'en' => 'English']),
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
