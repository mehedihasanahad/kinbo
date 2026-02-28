# DATABASE.md — ecom-tech

**Project:** ecom-tech (Laravel 12 E-Commerce Platform)
**Date:** 2026-02-28
**Engine:** InnoDB
**Character Set:** utf8mb4
**Collation:** utf8mb4_unicode_ci
**PHP:** 8.2+ | **Laravel:** 12.x
**Application Tables:** 31
**Locales:** `en` (English), `bn` (Bengali / বাংলা)

---

## Table of Contents

### Domain A — Auth & Users
1. [users](#a1-users)
2. [user_addresses](#a2-user_addresses)
3. [roles](#a3-roles)
4. [permissions](#a4-permissions)
5. [model_has_roles](#a5-model_has_roles)
6. [model_has_permissions](#a6-model_has_permissions)
7. [role_has_permissions](#a7-role_has_permissions)

### Domain B — Catalog
8. [brands](#b1-brands)
9. [categories](#b2-categories)
10. [category_translations](#b3-category_translations)
11. [products](#b4-products)
12. [product_translations](#b5-product_translations)
13. [product_images](#b6-product_images)
14. [product_variants](#b7-product_variants)
15. [product_variant_options](#b8-product_variant_options)

### Domain C — Commerce
16. [wishlists](#c1-wishlists)
17. [cart_items](#c2-cart_items)
18. [orders](#c3-orders)
19. [order_items](#c4-order_items)
20. [order_status_history](#c5-order_status_history)

### Domain D — Payment
21. [payment_transactions](#d1-payment_transactions)
22. [manual_payments](#d2-manual_payments)

### Domain E — Marketing
23. [coupons](#e1-coupons)
24. [coupon_usages](#e2-coupon_usages)
25. [shipping_zones](#e3-shipping_zones)
26. [shipping_zone_districts](#e4-shipping_zone_districts)
27. [shipping_rates](#e5-shipping_rates)

### Domain F — Content & Social Proof
28. [reviews](#f1-reviews)
29. [review_votes](#f2-review_votes)
30. [settings](#f3-settings)

### Domain G — Laravel System Tables
31. [sessions, cache, jobs, migrations](#g-laravel-system-tables)

---

## Naming Conventions

| Rule | Pattern | Example |
|---|---|---|
| Table names | `snake_case`, plural | `order_items` |
| Column names | `snake_case` | `unit_price` |
| Primary key | `id` | `id BIGINT UNSIGNED` |
| Foreign keys | `{table_singular}_id` | `product_id` |
| Boolean columns | `is_*` prefix | `is_active`, `is_featured` |
| Timestamps | `created_at`, `updated_at` | Laravel default |
| Soft deletes | `deleted_at` | nullable timestamp |
| Locale column | `locale CHAR(2)` | `'en'`, `'bn'` |
| Index names | `idx_{table}_{column}` | `idx_products_category_id` |
| Unique index names | `udx_{table}_{column}` | `udx_products_sku` |
| Composite index names | `idx_{table}_{col1}_{col2}` | `idx_orders_user_status` |

---

## Domain A — Auth & Users

### A1. `users`

> Stores all platform users: customers and administrators. Role differentiation is handled via `roles` table (spatie/laravel-permission). Soft deletes preserve order history.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `name` | `VARCHAR(191)` | NO | — | Full name |
| `email` | `VARCHAR(191)` | NO | — | Unique login email |
| `email_verified_at` | `TIMESTAMP` | YES | NULL | Email verification timestamp |
| `password` | `VARCHAR(255)` | YES | NULL | Hashed (nullable for social-only accounts) |
| `phone` | `VARCHAR(20)` | YES | NULL | Mobile number (BD format) |
| `avatar` | `VARCHAR(255)` | YES | NULL | Profile image path |
| `locale` | `CHAR(2)` | NO | `'en'` | Preferred language: `en` or `bn` |
| `provider` | `VARCHAR(30)` | YES | NULL | Social provider: `google`, `facebook` |
| `provider_id` | `VARCHAR(191)` | YES | NULL | Social provider UID |
| `is_active` | `TINYINT(1)` | NO | `1` | Account enabled flag |
| `remember_token` | `VARCHAR(100)` | YES | NULL | Laravel auth remember token |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |
| `deleted_at` | `TIMESTAMP` | YES | NULL | Soft delete |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_users_email` | UNIQUE | `email` |
| `idx_users_phone` | INDEX | `phone` |
| `idx_users_provider` | INDEX | `provider`, `provider_id` |
| `idx_users_is_active` | INDEX | `is_active` |

**Foreign Keys:** none

---

### A2. `user_addresses`

> Multiple saved addresses per user. One address per user can be default.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `label` | `VARCHAR(50)` | YES | NULL | e.g. `Home`, `Office` |
| `recipient_name` | `VARCHAR(191)` | NO | — | Name on the address |
| `phone` | `VARCHAR(20)` | NO | — | Contact number |
| `address_line` | `VARCHAR(255)` | NO | — | Street / house details |
| `city` | `VARCHAR(100)` | NO | — | City |
| `district` | `VARCHAR(100)` | NO | — | Bangladesh district |
| `upazila` | `VARCHAR(100)` | YES | NULL | Sub-district |
| `zip_code` | `VARCHAR(10)` | YES | NULL | Postal code |
| `is_default` | `TINYINT(1)` | NO | `0` | Default address flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_user_addresses_user_id` | INDEX | `user_id` |
| `idx_user_addresses_default` | INDEX | `user_id`, `is_default` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `user_id` | `users.id` | CASCADE |

---

### A3. `roles`

> spatie/laravel-permission compatible. Roles: `super_admin`, `admin`, `staff`, `customer`.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `name` | `VARCHAR(191)` | NO | — | Role name |
| `guard_name` | `VARCHAR(191)` | NO | — | Auth guard (e.g. `web`) |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_roles_name_guard` | UNIQUE | `name`, `guard_name` |

---

### A4. `permissions`

> spatie/laravel-permission compatible. Granular permissions (e.g. `manage_products`, `verify_payments`).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `name` | `VARCHAR(191)` | NO | — | Permission name |
| `guard_name` | `VARCHAR(191)` | NO | — | Auth guard |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_permissions_name_guard` | UNIQUE | `name`, `guard_name` |

---

### A5. `model_has_roles`

> Pivot: assigns roles to any model (polymorphic).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `role_id` | `BIGINT UNSIGNED` | NO | — | FK → roles.id |
| `model_type` | `VARCHAR(191)` | NO | — | e.g. `App\Models\User` |
| `model_id` | `BIGINT UNSIGNED` | NO | — | User ID |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `role_id`, `model_id`, `model_type` |
| `idx_model_has_roles_model` | INDEX | `model_id`, `model_type` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `role_id` | `roles.id` | CASCADE |

---

### A6. `model_has_permissions`

> Pivot: direct permissions assigned to models.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `permission_id` | `BIGINT UNSIGNED` | NO | — | FK → permissions.id |
| `model_type` | `VARCHAR(191)` | NO | — | Model class |
| `model_id` | `BIGINT UNSIGNED` | NO | — | Model ID |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `permission_id`, `model_id`, `model_type` |
| `idx_model_has_perms_model` | INDEX | `model_id`, `model_type` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `permission_id` | `permissions.id` | CASCADE |

---

### A7. `role_has_permissions`

> Pivot: permissions assigned to roles.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `permission_id` | `BIGINT UNSIGNED` | NO | — | FK → permissions.id |
| `role_id` | `BIGINT UNSIGNED` | NO | — | FK → roles.id |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `permission_id`, `role_id` |
| `idx_role_has_perms_role_id` | INDEX | `role_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `permission_id` | `permissions.id` | CASCADE |
| `role_id` | `roles.id` | CASCADE |

---

## Domain B — Catalog

### B1. `brands`

> Manufacturer / brand management. Products belong to one brand.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `name` | `VARCHAR(191)` | NO | — | Brand display name |
| `slug` | `VARCHAR(191)` | NO | — | URL-friendly identifier |
| `logo` | `VARCHAR(255)` | YES | NULL | Logo image path |
| `description` | `TEXT` | YES | NULL | Brand bio |
| `is_active` | `TINYINT(1)` | NO | `1` | Visibility flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_brands_slug` | UNIQUE | `slug` |
| `idx_brands_is_active` | INDEX | `is_active` |

---

### B2. `categories`

> Hierarchical (parent/child) product categories. Self-referencing via `parent_id`. Soft deletes prevent deletion while products exist.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `parent_id` | `BIGINT UNSIGNED` | YES | NULL | FK → categories.id (null = root) |
| `image` | `VARCHAR(255)` | YES | NULL | Category banner/icon path |
| `sort_order` | `SMALLINT UNSIGNED` | NO | `0` | Display ordering |
| `is_active` | `TINYINT(1)` | NO | `1` | Visibility flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |
| `deleted_at` | `TIMESTAMP` | YES | NULL | Soft delete |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_categories_parent_id` | INDEX | `parent_id` |
| `idx_categories_active_sort` | INDEX | `is_active`, `sort_order` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `parent_id` | `categories.id` | SET NULL |

> Note: `name` and `slug` are in `category_translations` (per locale).

---

### B3. `category_translations`

> Locale-specific names and slugs for categories (EN + BN).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `category_id` | `BIGINT UNSIGNED` | NO | — | FK → categories.id |
| `locale` | `CHAR(2)` | NO | — | `'en'` or `'bn'` |
| `name` | `VARCHAR(191)` | NO | — | Localized category name |
| `slug` | `VARCHAR(191)` | NO | — | Localized URL slug |
| `meta_title` | `VARCHAR(191)` | YES | NULL | SEO title |
| `meta_description` | `VARCHAR(255)` | YES | NULL | SEO description |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_cat_trans_cat_locale` | UNIQUE | `category_id`, `locale` |
| `idx_cat_trans_slug` | INDEX | `slug`, `locale` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `category_id` | `categories.id` | CASCADE |

---

### B4. `products`

> Core product record. Localized content lives in `product_translations`. Soft deletes preserve order history.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `category_id` | `BIGINT UNSIGNED` | NO | — | FK → categories.id |
| `brand_id` | `BIGINT UNSIGNED` | YES | NULL | FK → brands.id |
| `sku` | `VARCHAR(100)` | NO | — | Unique stock-keeping unit |
| `price` | `DECIMAL(10,2)` | NO | — | Regular price (BDT) |
| `sale_price` | `DECIMAL(10,2)` | YES | NULL | Discounted price; NULL = no sale |
| `stock` | `INT UNSIGNED` | NO | `0` | Available quantity |
| `low_stock_threshold` | `SMALLINT UNSIGNED` | NO | `5` | Alert below this quantity |
| `weight` | `DECIMAL(8,3)` | YES | NULL | Weight in KG (for shipping) |
| `is_active` | `TINYINT(1)` | NO | `1` | Published/visible flag |
| `is_featured` | `TINYINT(1)` | NO | `0` | Featured on homepage |
| `sort_order` | `SMALLINT UNSIGNED` | NO | `0` | Manual sort within category |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |
| `deleted_at` | `TIMESTAMP` | YES | NULL | Soft delete |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_products_sku` | UNIQUE | `sku` |
| `idx_products_category_active` | INDEX | `category_id`, `is_active` |
| `idx_products_brand_active` | INDEX | `brand_id`, `is_active` |
| `idx_products_featured` | INDEX | `is_featured`, `is_active` |
| `idx_products_price` | INDEX | `price` |
| `idx_products_sale_price` | INDEX | `sale_price` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `category_id` | `categories.id` | RESTRICT |
| `brand_id` | `brands.id` | SET NULL |

---

### B5. `product_translations`

> Locale-specific product content (EN + BN).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `locale` | `CHAR(2)` | NO | — | `'en'` or `'bn'` |
| `name` | `VARCHAR(191)` | NO | — | Localized product name |
| `slug` | `VARCHAR(191)` | NO | — | Localized URL slug |
| `short_description` | `TEXT` | YES | NULL | Brief summary (listing pages) |
| `description` | `LONGTEXT` | YES | NULL | Full product description |
| `meta_title` | `VARCHAR(191)` | YES | NULL | SEO title override |
| `meta_description` | `VARCHAR(255)` | YES | NULL | SEO description |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_prod_trans_prod_locale` | UNIQUE | `product_id`, `locale` |
| `idx_prod_trans_slug` | INDEX | `slug`, `locale` |
| `idx_prod_trans_name` | INDEX | `name`, `locale` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `product_id` | `products.id` | CASCADE |

---

### B6. `product_images`

> Gallery images for each product. One image is flagged primary (thumbnail).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `path` | `VARCHAR(255)` | NO | — | Stored image path |
| `alt_text` | `VARCHAR(191)` | YES | NULL | Accessibility alt text |
| `sort_order` | `SMALLINT UNSIGNED` | NO | `0` | Gallery order |
| `is_primary` | `TINYINT(1)` | NO | `0` | Main thumbnail flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_prod_images_product_id` | INDEX | `product_id` |
| `idx_prod_images_primary` | INDEX | `product_id`, `is_primary` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `product_id` | `products.id` | CASCADE |

---

### B7. `product_variants`

> Product variations (e.g. Size: L, Color: Red). Each variant has its own stock and price modifier.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `sku` | `VARCHAR(100)` | YES | NULL | Variant SKU (optional) |
| `price_modifier` | `DECIMAL(10,2)` | NO | `0.00` | Added to base price (can be negative) |
| `stock` | `INT UNSIGNED` | NO | `0` | Variant stock qty |
| `sort_order` | `SMALLINT UNSIGNED` | NO | `0` | Display order |
| `is_active` | `TINYINT(1)` | NO | `1` | Variant enabled flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_prod_variants_product_id` | INDEX | `product_id` |
| `udx_prod_variants_sku` | UNIQUE | `sku` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `product_id` | `products.id` | CASCADE |

---

### B8. `product_variant_options`

> Key-value option pairs belonging to a variant (e.g. `option_name=Size`, `option_value=XL`).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `variant_id` | `BIGINT UNSIGNED` | NO | — | FK → product_variants.id |
| `option_name` | `VARCHAR(50)` | NO | — | Attribute name: `Size`, `Color` |
| `option_value` | `VARCHAR(100)` | NO | — | Attribute value: `XL`, `Red` |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_variant_options_variant_id` | INDEX | `variant_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `variant_id` | `product_variants.id` | CASCADE |

---

## Domain C — Commerce

### C1. `wishlists`

> Users' saved products. One record per user-product pair.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_wishlists_user_product` | UNIQUE | `user_id`, `product_id` |
| `idx_wishlists_product_id` | INDEX | `product_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `user_id` | `users.id` | CASCADE |
| `product_id` | `products.id` | CASCADE |

---

### C2. `cart_items`

> Supports both authenticated users (user_id) and guests (session_id). One or the other must be set.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `user_id` | `BIGINT UNSIGNED` | YES | NULL | FK → users.id (null for guests) |
| `session_id` | `VARCHAR(191)` | YES | NULL | Guest session identifier |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `variant_id` | `BIGINT UNSIGNED` | YES | NULL | FK → product_variants.id |
| `quantity` | `SMALLINT UNSIGNED` | NO | `1` | Qty added to cart |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_cart_user_id` | INDEX | `user_id` |
| `idx_cart_session_id` | INDEX | `session_id` |
| `idx_cart_product_id` | INDEX | `product_id` |
| `idx_cart_user_product_variant` | INDEX | `user_id`, `product_id`, `variant_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `user_id` | `users.id` | CASCADE |
| `product_id` | `products.id` | CASCADE |
| `variant_id` | `product_variants.id` | SET NULL |

---

### C3. `orders`

> Core order record. Shipping info is denormalized (snapshot) so address changes don't affect past orders.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `order_number` | `VARCHAR(30)` | NO | — | Human-readable unique (e.g. `ORD-20260228-0001`) |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `coupon_id` | `BIGINT UNSIGNED` | YES | NULL | FK → coupons.id |
| `shipping_rate_id` | `BIGINT UNSIGNED` | YES | NULL | FK → shipping_rates.id |
| `subtotal` | `DECIMAL(10,2)` | NO | — | Sum of order_items.subtotal |
| `discount_amount` | `DECIMAL(10,2)` | NO | `0.00` | Coupon discount applied |
| `shipping_amount` | `DECIMAL(10,2)` | NO | `0.00` | Shipping charge |
| `tax_amount` | `DECIMAL(10,2)` | NO | `0.00` | Tax (VAT) if applicable |
| `total_amount` | `DECIMAL(10,2)` | NO | — | Final charged amount |
| `status` | `VARCHAR(20)` | NO | `'pending'` | `pending` `processing` `shipped` `delivered` `cancelled` `returned` |
| `payment_status` | `VARCHAR(20)` | NO | `'unpaid'` | `unpaid` `pending_verification` `paid` `refunded` `failed` |
| `payment_method` | `VARCHAR(20)` | NO | — | `cod` `bkash` `nagad` `sslcommerz` |
| `ship_name` | `VARCHAR(191)` | NO | — | Snapshot: recipient name |
| `ship_phone` | `VARCHAR(20)` | NO | — | Snapshot: recipient phone |
| `ship_address` | `VARCHAR(255)` | NO | — | Snapshot: street address |
| `ship_city` | `VARCHAR(100)` | NO | — | Snapshot: city |
| `ship_district` | `VARCHAR(100)` | NO | — | Snapshot: district |
| `ship_zip` | `VARCHAR(10)` | YES | NULL | Snapshot: postal code |
| `notes` | `TEXT` | YES | NULL | Customer order notes |
| `tracking_number` | `VARCHAR(100)` | YES | NULL | Courier tracking number |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_orders_order_number` | UNIQUE | `order_number` |
| `idx_orders_user_id` | INDEX | `user_id` |
| `idx_orders_user_status` | INDEX | `user_id`, `status` |
| `idx_orders_status_created` | INDEX | `status`, `created_at` |
| `idx_orders_payment_status` | INDEX | `payment_status` |
| `idx_orders_payment_method` | INDEX | `payment_method` |
| `idx_orders_coupon_id` | INDEX | `coupon_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `user_id` | `users.id` | RESTRICT |
| `coupon_id` | `coupons.id` | SET NULL |
| `shipping_rate_id` | `shipping_rates.id` | SET NULL |

---

### C4. `order_items`

> Line items belonging to an order. Product name, variant label, and unit price are **snapshots** taken at order time to prevent price drift.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `order_id` | `BIGINT UNSIGNED` | NO | — | FK → orders.id |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `variant_id` | `BIGINT UNSIGNED` | YES | NULL | FK → product_variants.id |
| `product_name` | `VARCHAR(191)` | NO | — | Snapshot: product name at time of order |
| `variant_label` | `VARCHAR(191)` | YES | NULL | Snapshot: e.g. `Size: L / Color: Red` |
| `unit_price` | `DECIMAL(10,2)` | NO | — | Snapshot: price per item at order time |
| `quantity` | `SMALLINT UNSIGNED` | NO | — | Qty ordered |
| `subtotal` | `DECIMAL(10,2)` | NO | — | `unit_price × quantity` |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_order_items_order_id` | INDEX | `order_id` |
| `idx_order_items_product_id` | INDEX | `product_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `order_id` | `orders.id` | CASCADE |
| `product_id` | `products.id` | RESTRICT |
| `variant_id` | `product_variants.id` | SET NULL |

---

### C5. `order_status_history`

> Audit trail of every status change on an order.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `order_id` | `BIGINT UNSIGNED` | NO | — | FK → orders.id |
| `status` | `VARCHAR(20)` | NO | — | New status value |
| `notes` | `TEXT` | YES | NULL | Admin/system notes for this transition |
| `changed_by` | `BIGINT UNSIGNED` | YES | NULL | FK → users.id (admin who made change) |
| `created_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_order_status_hist_order` | INDEX | `order_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `order_id` | `orders.id` | CASCADE |
| `changed_by` | `users.id` | SET NULL |

---

## Domain D — Payment

### D1. `payment_transactions`

> Full transaction log for all payment gateways (SSLCommerz, bKash API, Nagad API). Raw gateway response stored as JSON for audit.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `order_id` | `BIGINT UNSIGNED` | NO | — | FK → orders.id |
| `gateway` | `VARCHAR(20)` | NO | — | `sslcommerz` `bkash` `nagad` `cod` |
| `tran_id` | `VARCHAR(100)` | YES | NULL | Our transaction reference |
| `val_id` | `VARCHAR(100)` | YES | NULL | Gateway validation ID (SSLCommerz) |
| `bank_tran_id` | `VARCHAR(100)` | YES | NULL | Bank/MFS transaction ID |
| `amount` | `DECIMAL(10,2)` | NO | — | Amount charged |
| `currency` | `CHAR(3)` | NO | `'BDT'` | ISO currency code |
| `card_type` | `VARCHAR(50)` | YES | NULL | Card/wallet type (e.g. `Visa`, `bKash`) |
| `status` | `VARCHAR(20)` | NO | `'pending'` | `pending` `success` `failed` `cancelled` `refunded` |
| `raw_response` | `JSON` | YES | NULL | Full gateway response payload |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_pay_trans_order_id` | INDEX | `order_id` |
| `idx_pay_trans_tran_id` | INDEX | `tran_id` |
| `idx_pay_trans_val_id` | INDEX | `val_id` |
| `idx_pay_trans_gateway_status` | INDEX | `gateway`, `status` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `order_id` | `orders.id` | RESTRICT |

---

### D2. `manual_payments`

> Records for bKash / Nagad **Send Money** payments. Admin must verify the `transaction_id` before fulfilling the order.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `order_id` | `BIGINT UNSIGNED` | NO | — | FK → orders.id |
| `method` | `VARCHAR(10)` | NO | — | `bkash` or `nagad` |
| `sender_number` | `VARCHAR(20)` | NO | — | Customer's mobile number used to send |
| `transaction_id` | `VARCHAR(100)` | NO | — | TrxID provided by customer |
| `amount` | `DECIMAL(10,2)` | NO | — | Claimed sent amount |
| `screenshot_path` | `VARCHAR(255)` | YES | NULL | Optional uploaded screenshot |
| `status` | `VARCHAR(20)` | NO | `'pending'` | `pending` `verified` `rejected` |
| `verified_by` | `BIGINT UNSIGNED` | YES | NULL | FK → users.id (admin) |
| `verified_at` | `TIMESTAMP` | YES | NULL | When admin verified |
| `rejection_reason` | `VARCHAR(255)` | YES | NULL | Reason if rejected |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_manual_pay_order_id` | UNIQUE | `order_id` |
| `idx_manual_pay_tran_id` | INDEX | `transaction_id` |
| `idx_manual_pay_status` | INDEX | `status`, `created_at` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `order_id` | `orders.id` | CASCADE |
| `verified_by` | `users.id` | SET NULL |

---

## Domain E — Marketing

### E1. `coupons`

> Discount codes. Supports fixed-amount and percentage discounts, usage caps, product/category restrictions, and expiry. Soft deletes allow disabling without data loss.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `code` | `VARCHAR(50)` | NO | — | Unique coupon code (case-insensitive) |
| `type` | `VARCHAR(10)` | NO | — | `fixed` or `percent` |
| `value` | `DECIMAL(10,2)` | NO | — | Discount amount or percentage |
| `min_order_amount` | `DECIMAL(10,2)` | NO | `0.00` | Minimum cart value to apply |
| `max_discount_amount` | `DECIMAL(10,2)` | YES | NULL | Cap for percent discounts (NULL = no cap) |
| `max_uses` | `INT UNSIGNED` | YES | NULL | Total use limit (NULL = unlimited) |
| `used_count` | `INT UNSIGNED` | NO | `0` | Incremented on each use |
| `per_user_limit` | `TINYINT UNSIGNED` | NO | `1` | Max uses per customer |
| `product_ids` | `JSON` | YES | NULL | Restrict to specific product IDs |
| `category_ids` | `JSON` | YES | NULL | Restrict to specific category IDs |
| `starts_at` | `TIMESTAMP` | YES | NULL | Coupon valid from (NULL = always) |
| `expires_at` | `TIMESTAMP` | YES | NULL | Coupon valid until (NULL = no expiry) |
| `is_active` | `TINYINT(1)` | NO | `1` | Enabled flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |
| `deleted_at` | `TIMESTAMP` | YES | NULL | Soft delete |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_coupons_code` | UNIQUE | `code` |
| `idx_coupons_active_expires` | INDEX | `is_active`, `expires_at` |

---

### E2. `coupon_usages`

> Records each time a coupon is applied to an order. Used to enforce per-user and global usage limits.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `coupon_id` | `BIGINT UNSIGNED` | NO | — | FK → coupons.id |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `order_id` | `BIGINT UNSIGNED` | NO | — | FK → orders.id |
| `discount_amount` | `DECIMAL(10,2)` | NO | — | Actual discount applied |
| `created_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_coupon_usage_unique` | UNIQUE | `coupon_id`, `user_id`, `order_id` |
| `idx_coupon_usage_user` | INDEX | `user_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `coupon_id` | `coupons.id` | RESTRICT |
| `user_id` | `users.id` | RESTRICT |
| `order_id` | `orders.id` | RESTRICT |

---

### E3. `shipping_zones`

> Named delivery zones (e.g. "Dhaka City", "Outside Dhaka", "Chittagong"). Each zone has its own districts and rates.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `name` | `VARCHAR(100)` | NO | — | Zone display name |
| `description` | `VARCHAR(255)` | YES | NULL | Optional description |
| `is_active` | `TINYINT(1)` | NO | `1` | Zone enabled flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_shipping_zones_active` | INDEX | `is_active` |

---

### E4. `shipping_zone_districts`

> Maps Bangladesh districts to shipping zones.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `zone_id` | `BIGINT UNSIGNED` | NO | — | FK → shipping_zones.id |
| `district_name` | `VARCHAR(100)` | NO | — | Bangladesh district name (e.g. `Dhaka`) |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_zone_district` | UNIQUE | `zone_id`, `district_name` |
| `idx_zone_districts_district` | INDEX | `district_name` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `zone_id` | `shipping_zones.id` | CASCADE |

---

### E5. `shipping_rates`

> Pricing and time estimates for each shipping method within a zone.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `zone_id` | `BIGINT UNSIGNED` | NO | — | FK → shipping_zones.id |
| `method_name` | `VARCHAR(100)` | NO | — | e.g. `Standard Courier`, `Express` |
| `cost` | `DECIMAL(10,2)` | NO | — | Shipping fee (BDT) |
| `free_shipping_above` | `DECIMAL(10,2)` | YES | NULL | Free if order total ≥ this (NULL = never free) |
| `estimated_days_min` | `TINYINT UNSIGNED` | YES | NULL | Minimum delivery days |
| `estimated_days_max` | `TINYINT UNSIGNED` | YES | NULL | Maximum delivery days |
| `is_active` | `TINYINT(1)` | NO | `1` | Rate enabled flag |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_shipping_rates_zone_id` | INDEX | `zone_id` |
| `idx_shipping_rates_active` | INDEX | `zone_id`, `is_active` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `zone_id` | `shipping_zones.id` | CASCADE |

---

## Domain F — Content & Social Proof

### F1. `reviews`

> Customer product reviews with rating. Optionally tied to a verified purchase (order_id).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `product_id` | `BIGINT UNSIGNED` | NO | — | FK → products.id |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `order_id` | `BIGINT UNSIGNED` | YES | NULL | FK → orders.id (verified purchase badge) |
| `rating` | `TINYINT UNSIGNED` | NO | — | 1–5 star rating |
| `title` | `VARCHAR(191)` | YES | NULL | Optional review headline |
| `body` | `TEXT` | YES | NULL | Full review text |
| `is_approved` | `TINYINT(1)` | NO | `0` | Admin moderation flag |
| `helpful_count` | `INT UNSIGNED` | NO | `0` | Cached count from review_votes |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `idx_reviews_product_approved` | INDEX | `product_id`, `is_approved` |
| `idx_reviews_product_rating` | INDEX | `product_id`, `rating` |
| `idx_reviews_user_id` | INDEX | `user_id` |
| `idx_reviews_order_id` | INDEX | `order_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `product_id` | `products.id` | CASCADE |
| `user_id` | `users.id` | RESTRICT |
| `order_id` | `orders.id` | SET NULL |

---

### F2. `review_votes`

> Tracks "helpful / not helpful" votes on reviews. One vote per user per review.

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `review_id` | `BIGINT UNSIGNED` | NO | — | FK → reviews.id |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users.id |
| `is_helpful` | `TINYINT(1)` | NO | — | `1` = helpful, `0` = not helpful |
| `created_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_review_votes_user_review` | UNIQUE | `review_id`, `user_id` |

**Foreign Keys:**

| Column | References | On Delete |
|---|---|---|
| `review_id` | `reviews.id` | CASCADE |
| `user_id` | `users.id` | CASCADE |

---

### F3. `settings`

> Key-value store for site-wide configuration (store name, logo, contact info, social links, maintenance mode, etc.).

| Column | Type | Null | Default | Comment |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | NO | — | Primary key |
| `group` | `VARCHAR(50)` | NO | `'general'` | Logical group (`general`, `payment`, `seo`, `mail`) |
| `key` | `VARCHAR(100)` | NO | — | Setting key (unique) |
| `value` | `TEXT` | YES | NULL | Setting value |
| `is_public` | `TINYINT(1)` | NO | `0` | Expose to frontend JS/API |
| `created_at` | `TIMESTAMP` | YES | NULL | |
| `updated_at` | `TIMESTAMP` | YES | NULL | |

**Indexes:**

| Index | Type | Columns |
|---|---|---|
| `PRIMARY` | PRIMARY | `id` |
| `udx_settings_key` | UNIQUE | `key` |
| `idx_settings_group` | INDEX | `group` |

---

## Domain G — Laravel System Tables

> These tables are created by Laravel's default migrations and package migrations. They are documented here for completeness — do not modify them manually.

| Table | Purpose |
|---|---|
| `migrations` | Tracks which migration files have been run |
| `password_reset_tokens` | Password reset token storage (email + token + created_at) |
| `sessions` | Database session driver storage |
| `cache` | Database cache driver storage |
| `cache_locks` | Cache atomic lock storage |
| `jobs` | Laravel queue job table |
| `job_batches` | Laravel batch job tracking |
| `failed_jobs` | Failed job records with payload and exception |
| `notifications` | Laravel database notification channel (polymorphic) |

---

## ERD — Entity Relationship Summary

```
USERS ─────────────────────────────────────────────────────
  │ 1:N  user_addresses
  │ 1:N  orders
  │ 1:N  reviews
  │ 1:N  cart_items
  │ 1:N  wishlists
  │ N:M  roles  (via model_has_roles)
  │ N:M  permissions  (via model_has_permissions)

ROLES ──────────────────────────────────────────────────────
  │ N:M  permissions  (via role_has_permissions)

CATEGORIES ─────────────────────────────────────────────────
  │ 1:N  categories (self-ref: parent_id)
  │ 1:N  category_translations
  │ 1:N  products

BRANDS ─────────────────────────────────────────────────────
  │ 1:N  products

PRODUCTS ───────────────────────────────────────────────────
  │ 1:N  product_translations
  │ 1:N  product_images
  │ 1:N  product_variants ──── 1:N product_variant_options
  │ 1:N  order_items
  │ 1:N  reviews
  │ N:M  users  (via wishlists)
  │ N:M  users  (via cart_items)

ORDERS ─────────────────────────────────────────────────────
  │ 1:N  order_items
  │ 1:N  order_status_history
  │ 1:N  payment_transactions
  │ 1:1  manual_payments  (optional)
  │ N:M  coupons  (via coupon_usages)
  │ 1:N  reviews  (verified purchase link)

SHIPPING_ZONES ─────────────────────────────────────────────
  │ 1:N  shipping_zone_districts
  │ 1:N  shipping_rates
```

---

## Index Strategy — Rationale

### Why index every FK column?
MySQL does not automatically create indexes on FK columns. Without an index, any FK lookup causes a full table scan. Every `*_id` FK column has at minimum one `INDEX`.

### Composite indexes — design rules
A composite index is useful when queries filter by multiple columns together:

| Table | Composite Index | Justifies Query Pattern |
|---|---|---|
| `products` | `(category_id, is_active)` | `WHERE category_id = ? AND is_active = 1` |
| `products` | `(is_featured, is_active)` | `WHERE is_featured = 1 AND is_active = 1` |
| `orders` | `(user_id, status)` | `WHERE user_id = ? AND status = 'delivered'` |
| `orders` | `(status, created_at)` | Admin order list filtered by status + date range |
| `reviews` | `(product_id, is_approved)` | `WHERE product_id = ? AND is_approved = 1` |
| `coupons` | `(is_active, expires_at)` | `WHERE is_active = 1 AND expires_at > NOW()` |
| `manual_payments` | `(status, created_at)` | Admin verification queue sorted by date |

### Columns requiring UNIQUE
Uniqueness is a business constraint, not just a performance hint. MySQL enforces it at the storage level:

| Table | Unique Column(s) | Reason |
|---|---|---|
| `users` | `email` | One account per email |
| `products` | `sku` | No duplicate stock codes |
| `orders` | `order_number` | No duplicate order references |
| `coupons` | `code` | No duplicate coupon codes |
| `category_translations` | `(category_id, locale)` | One translation per locale |
| `product_translations` | `(product_id, locale)` | One translation per locale |
| `wishlists` | `(user_id, product_id)` | One wishlist entry per product |
| `review_votes` | `(review_id, user_id)` | One vote per reviewer |
| `manual_payments` | `order_id` | One manual payment record per order |
| `shipping_zone_districts` | `(zone_id, district_name)` | No duplicate district in same zone |
| `settings` | `key` | No duplicate setting keys |

---

## MySQL Optimization Notes

### Storage Engine
All tables use **InnoDB** for:
- Row-level locking (better concurrency vs MyISAM table locks)
- ACID-compliant transactions
- Foreign key enforcement
- MVCC for consistent reads

### Character Set
`utf8mb4` with `utf8mb4_unicode_ci` collation:
- Full Unicode support including Bengali (BN) characters
- `utf8mb4_unicode_ci` = case-insensitive, accent-sensitive — correct for product names
- **Important:** VARCHAR index columns are limited to `VARCHAR(191)` due to InnoDB's 767-byte index limit with `utf8mb4` (4 bytes/char × 191 = 764 bytes)

### DECIMAL vs FLOAT for money
Always use `DECIMAL(10,2)` — never `FLOAT` or `DOUBLE` — for monetary values. Floating-point types cause rounding errors in financial calculations.

### Soft Deletes
`deleted_at` is added only to `users`, `products`, `categories`, `coupons`. Other tables use hard deletes. Over-using soft deletes degrades query performance by adding `WHERE deleted_at IS NULL` to every query.

### JSON columns
`raw_response` in `payment_transactions` and `product_ids`/`category_ids` in `coupons` use the `JSON` type (MySQL 5.7.8+). MySQL validates JSON at write time and allows `JSON_EXTRACT()` queries. Do **not** index JSON columns directly — extract frequently-queried fields to generated columns if needed.

### `orders` table — high-write considerations
The `orders` table will grow continuously. Consider:
- **Partitioning by range** on `created_at` (year/month) once row count exceeds ~5 million
- Archiving delivered/cancelled orders older than 2 years to an `orders_archive` table
- Keeping the working set small improves index efficiency

### Query cache hints (application-level)
Since MySQL 8.0 removed the query cache, use **Laravel's cache layer** (Redis recommended):
- Cache product listings: `Cache::remember("products.category.{$id}", 600, ...)`
- Cache settings: `Cache::rememberForever('settings.all', ...)`
- Invalidate on write in the respective Observers

### Connection pooling
Use **PgBouncer** or configure Laravel's `DB_CONNECTION` pool settings. For high traffic, set `mysql.options.PDO::ATTR_PERSISTENT = false` and rely on a connection pool at the infrastructure layer (e.g. ProxySQL).

---

## Summary Table Count

| Domain | Tables | Count |
|---|---|---|
| A — Auth & Users | users, user_addresses, roles, permissions, model_has_roles, model_has_permissions, role_has_permissions | 7 |
| B — Catalog | brands, categories, category_translations, products, product_translations, product_images, product_variants, product_variant_options | 8 |
| C — Commerce | wishlists, cart_items, orders, order_items, order_status_history | 5 |
| D — Payment | payment_transactions, manual_payments | 2 |
| E — Marketing | coupons, coupon_usages, shipping_zones, shipping_zone_districts, shipping_rates | 5 |
| F — Content | reviews, review_votes, settings | 3 |
| G — Laravel System | sessions, cache, jobs, migrations, notifications, etc. | ~9 |
| **Total (application)** | | **31** |

---

*Document maintained at `/DATABASE.md`. All migration files should be derived from this document.*
