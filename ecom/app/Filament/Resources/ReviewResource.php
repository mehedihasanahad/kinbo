<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    use HasResourcePermissions;

    protected static string $viewPermission   = 'view_reviews';
    protected static string $editPermission   = 'approve_reviews';
    protected static string $createPermission = '';
    protected static string $deletePermission = 'delete_reviews';

    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) Review::where('is_approved', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->maxLength(191)->nullable(),
                Forms\Components\Textarea::make('body')->rows(4)->required()->columnSpanFull(),
                Forms\Components\Select::make('rating')
                    ->options([1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'])
                    ->required(),
                Forms\Components\Toggle::make('is_approved')->label('Approved')->inline(false),
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Customer'),
                Infolists\Components\TextEntry::make('product.translations')
                    ->label('Product')
                    ->getStateUsing(fn ($record) => $record->product?->getTranslation('en')?->name ?? '—'),
                Infolists\Components\TextEntry::make('rating')
                    ->getStateUsing(fn ($record) => str_repeat('★', $record->rating)),
                Infolists\Components\IconEntry::make('is_approved')->boolean()->label('Approved'),
                Infolists\Components\TextEntry::make('title')->placeholder('—'),
                Infolists\Components\TextEntry::make('body')->columnSpanFull(),
                Infolists\Components\TextEntry::make('created_at')->dateTime(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Customer')->searchable(),

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->getStateUsing(fn ($record) => $record->product?->getTranslation('en')?->name ?? '—'),

                Tables\Columns\TextColumn::make('rating')
                    ->getStateUsing(fn ($record) => str_repeat('★', $record->rating))
                    ->color('warning'),

                Tables\Columns\TextColumn::make('title')->limit(40)->placeholder('—'),

                Tables\Columns\TextColumn::make('body')->limit(60)->wrap(),

                Tables\Columns\IconColumn::make('is_approved')->boolean()->label('Approved'),

                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')->label('Approved'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->label('Approve')
                    ->visible(fn ($record) => ! $record->is_approved)
                    ->action(function ($record) {
                        $record->update(['is_approved' => true]);
                        Notification::make()->title('Review approved')->success()->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->delete();
                        Notification::make()->title('Review deleted')->danger()->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'view'  => Pages\ViewReview::route('/{record}'),
            'edit'  => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
