# Database & Migrations

## Core Rules
- Every migration must implement a complete, working `down()` method.
- Never modify an existing migration — create a new one.
- Migrations run in order; test `migrate:fresh` locally before pushing.

## Column Conventions
- Primary key: default `id` (unsigned big integer auto-increment) unless justified.
- Foreign keys: use `foreignId('user_id')->constrained()->cascadeOnDelete()`.
- Nullable foreign keys: `->nullable()->nullOnDelete()`.
- Timestamps: `$table->timestamps()` on every table unless explicitly unnecessary.
- Soft deletes: `$table->softDeletes()` on recoverable models (Order, Product, User).
- Money columns: store as **integer** (cents/paisa) — never `decimal` or `float`.
- Boolean columns: `$table->boolean('is_active')->default(false)`.
- Status/type columns: use `string` + PHP enum cast on the model, or native DB enum for stable sets.

## Indexing
- Index every column used in `WHERE`, `ORDER BY`, `JOIN`, or foreign key lookups.
- Use composite indexes when queries filter on multiple columns together.
- Unique indexes for business-unique constraints (e.g. `slug`, `sku`, `email`).

```php
$table->string('slug')->unique();
$table->index(['status', 'created_at']);
$table->foreignId('category_id')->constrained()->cascadeOnDelete();
```

## Naming
- Table names: plural snake_case (`order_items`, `product_variants`).
- Pivot tables: alphabetical model names joined by underscore (`product_tag`, not `tag_product`).
- Migration file: `{verb}_{description}_table` — `create_products_table`, `add_sku_to_products_table`.

## Seeders & Factories
- Use factories for fake/test data; seeders for environment-specific fixtures.
- Production seeders must be **idempotent** — use `firstOrCreate` or `updateOrCreate`.
- `DatabaseSeeder` calls individual seeders in dependency order.
- Factories must define `definition()` completely using `fake()` helper.
- Never call `factory()->create()` in migrations.

## Performance
- Avoid adding columns to large tables without a plan for zero-downtime migration.
- Use `after()` to control column ordering when altering tables.
- Batch inserts in seeders: `Model::insert([...])` instead of looping `->create()`.
