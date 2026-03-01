# E-Commerce Platform — Features Due to Implement

**Project:** ecom-tech
**Framework:** Laravel 12 / Filament 3.3
**Last Updated:** 2026-03-01

> This document lists features from `FEATURES.md` that are **not yet fully implemented**, grouped by priority. Status codes: ✗ Not started | ⚠ Partial (model/structure ready, no UI/logic)

---

## Priority 1 — Core Missing Functionality

### 1.1 Wishlist ✅ DONE

- [x] `WishlistController` — `store()`, `destroy()`, `index()`, `moveToCart()`
- [x] Routes: `GET /wishlist`, `POST /wishlist`, `DELETE /wishlist/{product}`, `POST /wishlist/{product}/move-to-cart`
- [x] AJAX toggle handler (add/remove without page reload, updates SVG fill + badge)
- [x] Wishlist page view (`resources/views/wishlist/index.blade.php`)
- [x] "Move to Cart" button on wishlist page
- [x] Wishlist item count badge in header (ViewComposer + `wishlist-badge` class)
- [x] Wishlist button wired in product card (AJAX, filled heart when wishlisted)
- [x] Wishlist button wired in product detail page (AJAX, pre-filled state from controller)
- [x] Translation keys added to `lang/en/front.php` and `lang/bn/front.php`

---

### 1.2 Reviews & Ratings — Frontend ✅ DONE

- [x] Review submission form on product detail page (star picker + title + body)
- [x] `ReviewController` — `store()` with validation, `vote()` for helpful toggle
- [x] Routes: `POST /products/{product}/reviews`, `POST /reviews/{review}/vote`
- [x] Verified purchase detection (links `order_id` if user has a delivered order for this product)
- [x] Success/error flash + auto-opens reviews tab on redirect
- [x] Duplicate review prevention (checked in controller + "already reviewed" message in UI)
- [x] Helpful vote toggle (AJAX, togglable, updates count in real time)
- [x] Translation keys added (`helpful`, `review_rating`, `review_title/body`, `review_submit`, `review_submitted`, `review_already_submitted`, `review_login_to_write`)

---

### 1.3 Email Notifications ✅ DONE

- [x] SMTP already configured in `.env` (Gmail)
- [x] **Order Confirmation** — `app/Mail/OrderConfirmation.php` + `resources/views/emails/order-confirmation.blade.php`
- [x] **Payment Verified** — `app/Mail/PaymentVerified.php` + `resources/views/emails/payment-verified.blade.php`
- [x] **Payment Rejected** — `app/Mail/PaymentRejected.php` + `resources/views/emails/payment-rejected.blade.php`
- [x] **Order Shipped** — `app/Mail/OrderShipped.php` + `resources/views/emails/order-shipped.blade.php` (includes tracking number)
- [x] **Order Delivered** — `app/Mail/OrderDelivered.php` + `resources/views/emails/order-delivered.blade.php`
- [x] Shared Blade email layout — `resources/views/emails/layout.blade.php`
- [x] Fired from `CheckoutController::placeOrder()` — order confirmation on placement
- [x] Fired from `ManualPaymentResource` verify/reject actions — payment verified/rejected
- [x] Fired from `OrderResource\Pages\EditOrder::afterSave()` — shipped + delivered; also logs `OrderStatusHistory`
- [ ] ~~Low Stock Alert (admin)~~ — deferred to 4.3 Inventory Management
- [ ] ~~New Order Alert (admin)~~ — deferred to 4.3 Inventory Management

---

### 1.4 Product Search & Filters ✅ DONE

- [x] Search bar in header wired — `GET /search?q=` → `ShopController@category` (no separate controller)
- [x] Full-text search on `product_translations.name` and `short_description` (LIKE query)
- [x] Search results reuse existing `shop/category.blade.php` — no new view needed
- [x] Filter sidebar (brands, price range) works on search results; preserved via hidden `q` input
- [x] Sort options work on search results (newest, price asc/desc, biggest discount)
- [x] Filters + sort + pagination all preserve `?q=` via `withQueryString()`
- [x] Breadcrumb shows "Search results for ':q'" when in search mode
- [x] Empty state shows search-specific message (`search_empty` key)
- [x] Available brands sidebar filtered to match only brands in search results
- [x] Translation keys added: `search_results`, `search_empty` (EN + BN)
- [x] Category page with filters was already fully implemented

---

## Priority 2 — Payments & Commerce

### 2.1 SSLCommerz Payment Gateway ⚠ (model/table ready, no implementation)

- [ ] Install package: `sslcommerz/sslcommerz-laravel` or custom cURL
- [ ] Add env vars: `SSLCOMMERZ_STORE_ID`, `SSLCOMMERZ_STORE_PASSWORD`, `SSLCOMMERZ_IS_LIVE`
- [ ] `PaymentController` with: `initiate()`, `success()`, `fail()`, `cancel()`, `ipn()`
- [ ] Routes (remove CSRF for IPN, use webhook signature instead):
  ```
  POST /payment/initiate
  POST /payment/success
  POST /payment/fail
  POST /payment/cancel
  POST /payment/ipn
  ```
- [ ] Session payload builder (customer info, order info, URLs)
- [ ] IPN re-validation with SSLCommerz validation API (`val_id`)
- [ ] Auto-update order payment status on IPN success
- [ ] Transaction log stored in `payment_transactions` table
- [ ] Sandbox ↔ Live toggle via `SSLCOMMERZ_IS_LIVE` config
- [ ] "Pay Online (SSLCommerz)" option shown on checkout

---

### 2.2 Invoice / PDF Download ✗

- [ ] Install: `composer require barryvdh/laravel-dompdf`
- [ ] Invoice Blade template (`resources/views/orders/invoice.blade.php`)
- [ ] Route: `GET /orders/{order}/invoice` → `OrderController@invoice`
- [ ] PDF generation with order details, items, totals, addresses
- [ ] "Download Invoice" button on order detail page (`orders/show.blade.php`)
- [ ] Admin: Download invoice from Filament `OrderResource`

---

### 2.3 Return / Refund Flow ✗

- [ ] `ReturnRequest` model and migration (`order_id`, `user_id`, `reason`, `status`, `admin_notes`)
- [ ] "Request Return" button on order detail (within allowed window, e.g. 7 days after delivery)
- [ ] `ReturnController` — `store()`, `show()`
- [ ] Route: `POST /orders/{order}/return`, `GET /orders/{order}/return`
- [ ] Filament `ReturnRequestResource` — admin reviews and approves/rejects
- [ ] On approval: update order payment_status to `refunded`
- [ ] Email notification to customer on return approval/rejection

---

## Priority 3 — User Account & Profile

### 3.1 Address Book Management UI ⚠ (model + checkout integration exist, no standalone CRUD)

- [ ] `UserAddressController` — `index()`, `store()`, `update()`, `destroy()`, `setDefault()`
- [ ] Routes under `middleware('auth')`:
  ```
  GET    /account/addresses
  POST   /account/addresses
  PUT    /account/addresses/{address}
  DELETE /account/addresses/{address}
  POST   /account/addresses/{address}/default
  ```
- [ ] Address list view (`resources/views/account/addresses/index.blade.php`)
- [ ] Add/Edit address form (modal or inline)
- [ ] "Set as Default" button
- [ ] Link from user account menu

---

### 3.2 Customer Dashboard / Account Hub ⚠ (orders + profile exist)

- [ ] Unified account dashboard (`resources/views/account/index.blade.php`)
- [ ] Account navigation sidebar: Dashboard, Orders, Addresses, Wishlist, Reviews, Settings
- [ ] "My Reviews" tab — list reviews the user has submitted
- [ ] Profile picture upload (avatar stored in `public/avatars/`)
- [ ] Account deletion confirmation flow

---

### 3.3 Social Login ✗

- [ ] Install: `composer require laravel/socialite`
- [ ] Add env vars: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT`
- [ ] `SocialAuthController` — `redirect()`, `callback()` (per provider)
- [ ] Routes: `GET /auth/google/redirect`, `GET /auth/google/callback`
- [ ] Handle new vs. existing user (match by email, link provider_id)
- [ ] "Login with Google" button on login and register pages
- [ ] Optional: Facebook login (requires Meta app approval)

---

## Priority 4 — Admin Panel Additions

### 4.1 Reporting & Analytics ✗

- [ ] Sales report: revenue by date range, with chart
- [ ] Orders report: order counts by status
- [ ] Top products report: best sellers by quantity and revenue
- [ ] Customer report: new customers over time
- [ ] Export to CSV: orders, products, customers
- [ ] Filament custom pages for reports (or `filament/filament-chart.js` widgets)

---

### 4.2 Staff Roles & Permissions ⚠ (models exist, not enforced)

- [ ] Define permission list (e.g. `manage_orders`, `manage_products`, `verify_payments`)
- [ ] Seed default roles: `admin` (all), `staff` (orders + payments), `editor` (products only)
- [ ] Gate checks in Filament resources (`canAccess()`, `canCreate()`, etc.)
- [ ] Staff management UI in Filament (assign roles to users)
- [ ] Prevent non-admin staff from accessing settings page

---

### 4.3 Inventory Management Enhancements ⚠

- [ ] **Low stock alert**: admin notification when `stock <= low_stock_threshold`
  - [ ] Add `low_stock_threshold` field to `products` table
  - [ ] Queue job / scheduled command to check and notify
- [ ] **Stock audit log**: track every stock change with reason (order, manual edit, return)
- [ ] Bulk stock update tool in `ProductResource`

---

### 4.4 Order Tracking (Courier Number) ⚠

- [ ] Add `tracking_number` and `courier` fields to `orders` table (migration)
- [ ] Input in `OrderResource` when status = Shipped
- [ ] Display tracking number on order detail page (`orders/show.blade.php`)
- [ ] Include tracking number in "Order Shipped" email

---

## Priority 5 — SEO & Performance

### 5.1 Open Graph & Structured Data ✗

- [ ] Open Graph meta tags in `layouts/app.blade.php` (title, description, image)
- [ ] Product pages: OG image = primary product image
- [ ] JSON-LD structured data (`Product` schema) on product detail page

### 5.2 Sitemap ✗

- [ ] Install: `composer require spatie/laravel-sitemap`
- [ ] `SitemapController@generate` or Artisan command
- [ ] Include: home, category pages, product pages
- [ ] Auto-regenerate on product/category create/update (Observer or job)
- [ ] Route: `GET /sitemap.xml`

### 5.3 Image Optimization ✗

- [ ] Install: `composer require intervention/image`
- [ ] Resize + compress product images on upload (max 1200px, WebP conversion)
- [ ] Generate thumbnails for product cards (400×400)
- [ ] Apply in `ProductResource` image upload handler

---

## Packages to Install

| Package | Purpose | Priority |
|---|---|---|
| `barryvdh/laravel-dompdf` | PDF invoice generation | P2 |
| `laravel/socialite` | Google/Facebook OAuth | P3 |
| `spatie/laravel-sitemap` | XML sitemap | P5 |
| `intervention/image` | Image resize/WebP | P5 |
| `sslcommerz/sslcommerz-laravel` | Payment gateway | P2 |

---

## Environment Variables Still Needed

```env
# SSLCommerz
SSLCOMMERZ_STORE_ID=
SSLCOMMERZ_STORE_PASSWORD=
SSLCOMMERZ_IS_LIVE=false

# Google OAuth (Social Login)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT="${APP_URL}/auth/google/callback"

# Mail (production)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@myshop.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Quick Status Reference

| Feature | Status | Priority |
|---|---|---|
| Wishlist (functionality) | ✅ Done | P1 |
| Reviews — submit form | ✅ Done | P1 |
| Email notifications | ✅ Done | P1 |
| Product search & filters | ✅ Done | P1 |
| Category page with filters | ✗ Not started | P1 |
| SSLCommerz integration | ⚠ Partial | P2 |
| Invoice PDF download | ✗ Not started | P2 |
| Return / Refund flow | ✗ Not started | P2 |
| Address book UI | ⚠ Partial | P3 |
| Account dashboard hub | ⚠ Partial | P3 |
| Social login (Google) | ✗ Not started | P3 |
| Admin reports | ✗ Not started | P4 |
| Staff role enforcement | ⚠ Partial | P4 |
| Low stock alerts | ✗ Not started | P4 |
| Courier tracking on orders | ⚠ Partial | P4 |
| Open Graph / JSON-LD | ✗ Not started | P5 |
| Sitemap generation | ✗ Not started | P5 |
| Image optimization | ✗ Not started | P5 |

---

*Generated from analysis of `FEATURES.md` vs. current codebase state (2026-03-01).*
