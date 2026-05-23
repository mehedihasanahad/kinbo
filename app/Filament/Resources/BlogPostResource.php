<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    use HasResourcePermissions;

    protected static string $viewPermission   = 'view_content';
    protected static string $createPermission = 'create_content';
    protected static string $editPermission   = 'edit_content';
    protected static string $deletePermission = 'delete_content';

    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Blog Posts';
    protected static ?string $modelLabel      = 'Blog Post';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Post Content')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                            $set('slug', Str::slug($state ?? ''))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(BlogPost::class, 'slug', ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Auto-generated from title. You can customise it.'),

                    Forms\Components\Textarea::make('excerpt')
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText('Short summary shown on the blog listing page.'),

                    Forms\Components\RichEditor::make('content')
                        ->required()
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blog/attachments')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Meta & Publishing')
                ->schema([
                    Forms\Components\Hidden::make('locale')
                        ->default('en'),

                    Forms\Components\TextInput::make('category')
                        ->maxLength(100)
                        ->placeholder('e.g. Tips, News, Offers'),

                    Forms\Components\TextInput::make('author_name')
                        ->maxLength(100)
                        ->placeholder('e.g. Admin Team'),

                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->disk('public')
                        ->directory('blog')
                        ->imageResizeMode('cover')
                        ->imageResizeTargetWidth(1200)
                        ->imageResizeTargetHeight(630),

                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->default(false)
                        ->reactive(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publish Date')
                        ->default(now())
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->disk('public')
                    ->width(60)
                    ->height(40)
                    ->defaultImageUrl(asset('images/placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('locale')
                    ->colors(['primary' => 'en'])
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->options(['en' => 'English']),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
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
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
