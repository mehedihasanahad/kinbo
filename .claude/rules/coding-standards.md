# Coding Standards

## PHP & Laravel Version
- PHP ^8.2 — use all modern features: readonly properties, enums, named arguments, match expressions, fibers, intersection types.
- Laravel 12.x — follow framework conventions; never fight the framework.

## Strict Typing
- Every PHP file must begin with `declare(strict_types=1);`.
- Never use `mixed` without an explicit docblock justification.
- Use union types (`int|string`) and nullable types (`?string`) — never rely on implicit coercion.

## Class Design
- Use `final` on classes that must not be extended (Services, DTOs, Actions, Jobs, Mailables).
- Use `readonly` constructor property promotion for immutable data objects.
- One class per file, namespace must match directory structure (PSR-4).
- Avoid static methods except for named constructors on Value Objects.

## Code Style
- Enforced by **Laravel Pint** (PSR-12 + Laravel preset). Run `./vendor/bin/pint` before every commit.
- Method names: `camelCase`. Class names: `PascalCase`. Constants: `UPPER_SNAKE_CASE`.
- No trailing whitespace, consistent 4-space indentation, single blank line at end of file.

## General Rules
- No `var_dump`, `dd`, `dump`, or `die` left in committed code.
- No commented-out code blocks — use git history instead.
- No magic numbers — extract to named constants or config values.
- Prefer early returns to reduce nesting depth.
- Avoid `else` after a `return` or `throw`.

## Naming
| Thing | Convention | Example |
|---|---|---|
| Model | singular PascalCase | `Product`, `OrderItem` |
| Controller | singular + Controller | `ProductController` |
| Service | singular + Service | `ProductService` |
| Job | verb phrase + Job | `SendNewsletterJob` |
| Event | past-tense noun | `OrderPlaced` |
| Listener | verb phrase | `SendOrderConfirmation` |
| Request | action + model + Request | `StoreProductRequest` |
| Policy | model + Policy | `ProductPolicy` |
| Observer | model + Observer | `ProductObserver` |
| Migration | snake_case description | `create_products_table` |
