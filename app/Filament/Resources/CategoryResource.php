<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    use HasResourcePermissions;

    protected static string $viewPermission   = 'view_categories';
    protected static string $createPermission = 'create_categories';
    protected static string $editPermission   = 'edit_categories';
    protected static string $deletePermission = 'delete_categories';

    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Category Info')->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(fn () => Category::active()->with('translations')
                        ->whereNull('parent_id')
                        ->get()->mapWithKeys(fn ($c) => [$c->id => $c->getTranslation('en')?->name ?? '#' . $c->id]))
                    ->nullable()
                    ->searchable(),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()->integer()->default(0),

                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(3),

            Forms\Components\Section::make('Translation (English)')->schema([
                Forms\Components\TextInput::make('translation_name')
                    ->label('Name')->required()->maxLength(191)
                    ->dehydrated(false)
                    ->afterStateHydrated(fn ($record, $set) =>
                        $set('translation_name', $record?->getTranslation('en')?->name)),

                Forms\Components\TextInput::make('translation_slug')
                    ->label('Slug')->maxLength(191)
                    ->dehydrated(false)
                    ->afterStateHydrated(fn ($record, $set) =>
                        $set('translation_slug', $record?->getTranslation('en')?->slug)),

                Forms\Components\Textarea::make('translation_description')
                    ->label('Description')->rows(3)->columnSpanFull()
                    ->dehydrated(false)
                    ->afterStateHydrated(fn ($record, $set) =>
                        $set('translation_description', $record?->getTranslation('en')?->description)),
            ])->columns(2),

            Forms\Components\Section::make('Image')->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('categories')
                    ->imageEditor()
                    ->imagePreviewHeight('160')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                    ->maxSize(1024)
                    ->nullable()
                    ->helperText('Recommended: square image, 300×300px minimum.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->getStateUsing(fn ($record) => $record->getTranslation('en')?->name ?? '—')
                    ->searchable(query: fn ($query, $search) =>
                        $query->whereHas('translations', fn ($q) =>
                            $q->where('locale', 'en')->where('name', 'like', "%{$search}%"))),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->getStateUsing(fn ($record) => $record->parent?->getTranslation('en')?->name ?? '—'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products'),

                Tables\Columns\IconColumn::make('is_active')->boolean(),

                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
