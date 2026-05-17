# Localization & Internationalization

## Core Rule
Every user-facing string must be translatable. No hardcoded English text in Blade, controllers, or Mailables.

## Helpers
- PHP code: `__('key')` or `trans('key')`.
- Blade templates: `{{ __('key') }}` or `@lang('key')`.
- Pluralization: `trans_choice('key', $count)`.

## Language Files
- Location: `lang/{locale}/` (e.g. `lang/en/`, `lang/bn/`).
- Organize by feature: `lang/en/products.php`, `lang/en/orders.php`, `lang/en/auth.php`.
- JSON files (`lang/en.json`) for simple key-value translations used across the app.
- Keep keys descriptive and namespaced: `'products.created_successfully'` not just `'success'`.

## Locale Detection
- `SetLocale` middleware is registered globally — it reads locale from session or user preference and calls `app()->setLocale()`.
- Do not call `app()->setLocale()` manually in controllers or views.
- Available locales must be defined in `config/app.php` under `available_locales`.

## Database Translations
- `CategoryTranslation` and `ProductTranslation` models handle DB-stored multilingual content.
- Always access translated fields through the relationship — never store raw JSON in the parent model.
- When creating/updating translatable models, persist translations for **all** configured locales simultaneously.

```php
// Store translations for all locales
foreach (config('app.available_locales') as $locale) {
    $product->translations()->updateOrCreate(
        ['locale' => $locale],
        ['name' => $data["name_{$locale}"], 'description' => $data["description_{$locale}"]]
    );
}
```

## Number & Currency Formatting
- Use `Number::currency($amount / 100, 'BDT')` for money display.
- Use `Number::format()` with locale for numeric formatting.
- Never hardcode currency symbols (৳, $) — use config or translation strings.
- Dates: use `Carbon` locale-aware formatting: `$date->locale(app()->getLocale())->isoFormat('LL')`.

## Validation Messages
- Validation error messages in Form Requests must use translatable strings.
- Override `messages()` and `attributes()` to use `__()` where default messages are insufficient.

## Emails
- All email subjects must use `__()`.
- Mailable `envelope()` subject: `new Envelope(subject: __('mail.order_confirmed'))`.
- Email templates in `resources/views/emails/` — use `@lang()` throughout.
