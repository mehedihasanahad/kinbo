# Security

## Input Handling
- **Always** use `$request->validated()` in store/update operations — never `$request->all()`, `$request->input()`, or `$_POST` directly.
- Validate and whitelist every user-supplied value before using it in a query or file operation.
- Use `Rule::in()` to restrict inputs to known sets (statuses, types, sort directions).

## Output & XSS
- Use `{{ }}` in Blade for all user-generated content — it auto-escapes HTML entities.
- Use `{!! !!}` **only** for trusted, pre-sanitized HTML (e.g. CKEditor output that has been purified server-side).
- Never render raw `$request->input()` directly in a view.

## Authorization
- Use Gates and Policies for all authorization — never raw role/permission string checks in controllers or views.
- Call `$this->authorize()` as the first line of any controller method that requires it.
- Protect all admin routes with Filament's built-in panel authentication.
- Use `@can` / `@cannot` in Blade — never `@if(auth()->user()->role === 'admin')`.

## CSRF
- CSRF protection is enabled by default for all web routes — never disable it.
- API routes are stateless and exempt from CSRF; ensure they are authenticated via Sanctum tokens instead.

## Authentication
- Password hashing: always via `Hash::make()` — never `md5()` or `sha1()`.
- Rate-limit login attempts — verify `throttle:auth` middleware is applied to auth routes.
- Email verification enforced via `verified` middleware on sensitive routes.
- Socialite OAuth callbacks must validate the provider's state parameter.

## File Uploads
- Validate: `mimes`, `max` size, and `dimensions` in Form Request rules.
- Store uploads outside `public/` or in `storage/app/public/` — never directly in `public/uploads/`.
- Process all images through `App\Services\ImageProcessor` — strip EXIF metadata, re-encode.
- Never execute uploaded files; never trust file extensions from the client.
- Generate random filenames for stored uploads — never use the original filename.

## SQL Injection
- Use Eloquent or query builder with parameter binding — never string-interpolate user input into queries.
- If raw SQL is unavoidable, use `DB::select(DB::raw('...'), [bindings])`.

## Secrets & Environment
- All secrets go in `.env` — never commit credentials, keys, or tokens to version control.
- Access config values via `config('app.key')` — never `$_ENV` or `getenv()` directly in app code.
- `.env` and `storage/` are in `.gitignore` — verify before every commit.

## Dependencies
- Keep `composer.json` and `package.json` dependencies up to date.
- Run `composer audit` periodically to check for known vulnerabilities in PHP packages.

## Headers & HTTPS
- Enforce HTTPS in production via server config or `TrustProxies` middleware.
- Set security headers (CSP, X-Frame-Options, X-Content-Type-Options) at the server or middleware level.
