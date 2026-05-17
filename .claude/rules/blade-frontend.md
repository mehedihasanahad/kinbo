# Blade & Frontend

## Blade Templates
- Template location: `resources/views/`.
- Layouts: `resources/views/layouts/` — use `AppLayout` and `GuestLayout` components.
- Partials: `resources/views/partials/` for reusable snippets.
- Page views: mirror route structure (e.g. `resources/views/products/show.blade.php`).

## Output Escaping
- Use `{{ }}` for all user-generated or dynamic content — auto-escapes HTML.
- Use `{!! !!}` **only** for pre-sanitized trusted HTML (e.g. rich-text editor output purified server-side).
- Never render raw `$request->input()` or unsanitized DB content with `{!! !!}`.

## Components
- Blade components: `app/View/Components/` + `resources/views/components/`.
- Use anonymous components (`resources/views/components/`) for simple, data-free partials.
- Use class-based components for any component with logic.
- Props must be declared with `@props([])` in anonymous components.
- No inline `<style>` or `<script>` tags — all styles in CSS files, scripts in JS files.

## Tailwind CSS
- Utility-first — compose classes directly in Blade.
- No custom CSS unless a utility class genuinely cannot achieve the result.
- Custom styles go in `resources/css/app.css` using `@layer components {}`.
- Responsive design: `sm:`, `md:`, `lg:`, `xl:` prefixes — mobile-first.
- Dark mode: use `dark:` prefix if the design requires it.

## JavaScript
- Alpine.js for lightweight reactivity in Blade pages.
- Keep `x-data` objects small and focused — extract to reusable `Alpine.data()` components for complex logic.
- No jQuery.
- Vite handles all bundling — import CSS/JS only via `@vite(['resources/css/app.css', 'resources/js/app.js'])`.
- Place additional scripts via `@push('scripts') ... @endpush` + `@stack('scripts')` in layout.

## Asset Pipeline
- All assets built with Vite.
- Never reference assets with `asset()` for Vite-managed files — use `@vite()` directive.
- Use `asset()` only for files in `public/` that are not Vite-managed (e.g. vendor assets).
- Images referenced in Blade: `<img src="{{ asset('images/logo.png') }}" alt="...">` — always include `alt`.

## Localization in Views
- Use `__('key')` or `@lang('key')` for every user-facing string.
- Never hardcode English strings in Blade templates.
- Use `:attribute` placeholders in translation strings for dynamic values.

## Forms
- All forms must include `@csrf`.
- Method spoofing for PUT/PATCH/DELETE: `@method('PUT')`.
- Use `old('field')` to repopulate inputs after validation failures.
- Display errors with `$errors->first('field')` or `@error('field')`.

## Performance
- Use `@once` for scripts/styles that should render only once in loops.
- Use `wire:` directives only if Livewire is adopted — do not add Livewire unless planned.
- Minimize Blade template logic — move complex conditions to view composers or the controller.
