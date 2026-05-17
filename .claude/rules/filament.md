# Filament Admin Panel

## Version
Filament 3.3 — use the v3 API exclusively. Do not reference v2 docs or patterns.

## Resource Structure
```
app/Filament/
  Resources/
    ProductResource.php          # Resource class
    ProductResource/
      Pages/
        ListProducts.php
        CreateProduct.php
        EditProduct.php
        ViewProduct.php          # optional
  Pages/                         # Custom standalone pages
  Widgets/                       # Dashboard widgets
  Concerns/                      # Shared traits
```

## Resource Rules
- One Resource class per Eloquent model managed in admin.
- Always define `navigationGroup()` and `navigationSort()` for logical sidebar grouping.
- Use `HasResourcePermissions` concern (already exists in this project) for all resources needing access control.
- Define `$model`, `$navigationIcon`, `$recordTitleAttribute` on every resource.
- Implement `getGloballySearchableAttributes()` for searchable resources.

## Forms
- Use Filament's built-in form components only — do not mix raw Blade inside Filament forms.
- Organize fields with `Section`, `Fieldset`, and `Grid` for readability.
- Use `Select::make()->relationship()` for relationship fields — never raw `select` with manual options.
- File uploads: use `FileUpload` component; configure `disk`, `directory`, `acceptedFileTypes`, `maxSize`.
- Repeaters for nested data (e.g. product variants, order items).

```php
Forms\Components\Section::make('Pricing')
    ->schema([
        Forms\Components\TextInput::make('price')
            ->numeric()
            ->prefix('৳')
            ->required(),
        Forms\Components\Toggle::make('is_active')
            ->default(true),
    ])->columns(2),
```

## Tables
- Define `columns()`, `filters()`, `actions()`, and `bulkActions()` explicitly.
- Use `Tables\Columns\TextColumn::make()->searchable()->sortable()` on key columns.
- Apply `->badge()` for status columns with color mapping.
- Use `SelectFilter` for enum/status filtering.
- Default sort: `->defaultSort('created_at', 'desc')` unless another order makes more sense.

## Widgets
- Dashboard widgets live in `app/Filament/Widgets/`.
- Register widgets in `AdminPanelProvider` or within specific resources — not globally unless needed everywhere.
- Use `StatsOverviewWidget` for KPI cards; `ChartWidget` for graphs.
- Cache expensive widget queries: use `protected static ?string $pollingInterval = null;` and cache manually.

## Custom Pages
- Custom Filament pages in `app/Filament/Pages/`.
- Register in `AdminPanelProvider::pages()`.
- Use `Filament\Actions\Action` for page-level actions.

## Authorization
- Panel authentication is handled by `AdminPanelProvider` — only users passing `canAccessPanel()` can enter.
- Resource-level permissions use `HasResourcePermissions` concern.
- Never bypass panel auth with direct controller routes into admin features.

## Notifications
- Use `Filament\Notifications\Notification::make()->send()` for in-panel flash notifications.
- Use `->sendToDatabase($recipient)` for persistent notifications.
