# E-Commerce Platform — Feature Document

**Project:** ecom-tech
**Framework:** Laravel
**Last Updated:** 2026-02-28
**Languages Supported:** English (EN) | Bengali / বাংলা (BN)

---

## Table of Contents

1. [Multi-Language Support](#1-multi-language-support)
2. [User & Authentication](#2-user--authentication)
3. [Product Management](#3-product-management)
4. [Category & Inventory](#4-category--inventory)
5. [Shopping Cart & Wishlist](#5-shopping-cart--wishlist)
6. [Checkout & Order Management](#6-checkout--order-management)
7. [Payment Gateway Integration](#7-payment-gateway-integration)
   - [Manual Payments (bKash / Nagad)](#71-manual-payments-bkash--nagad)
   - [SSL Commerz Integration](#72-ssl-commerz-integration)
8. [Coupon & Discount System](#8-coupon--discount-system)
9. [Reviews & Ratings](#9-reviews--ratings)
10. [Shipping & Delivery](#10-shipping--delivery)
11. [Admin Panel](#11-admin-panel)
12. [Notification System](#12-notification-system)
13. [SEO & Performance](#13-seo--performance)
14. [Security](#14-security)

---

## 1. Multi-Language Support

| Feature | EN | BN |
|---|---|---|
| Language switcher (header) | Language | ভাষা |
| Persistent language preference (session/cookie) | ✅ | ✅ |
| All UI labels, buttons, messages translated | ✅ | ✅ |
| Product names & descriptions (per locale) | ✅ | ✅ |
| Category names (per locale) | ✅ | ✅ |
| Email notifications (per locale) | ✅ | ✅ |
| Date/number format localization | ✅ | ✅ |
| RTL support not required (Bengali is LTR) | — | — |

**Implementation Notes:**
- Laravel `lang/en/` and `lang/bn/` JSON/PHP files
- Middleware to detect and set `app()->setLocale()`
- `spatie/laravel-translatable` for model-level translations (product name, description, category name)
- Language switcher stored in `session('locale')`

**Sample Translation Keys:**

```php
// lang/en/common.php
'add_to_cart'     => 'Add to Cart',
'buy_now'         => 'Buy Now',
'out_of_stock'    => 'Out of Stock',
'place_order'     => 'Place Order',
'payment_method'  => 'Payment Method',

// lang/bn/common.php
'add_to_cart'     => 'কার্টে যোগ করুন',
'buy_now'         => 'এখনই কিনুন',
'out_of_stock'    => 'স্টক নেই',
'place_order'     => 'অর্ডার করুন',
'payment_method'  => 'পেমেন্ট পদ্ধতি',
```

---

## 2. User & Authentication

| Feature | EN | BN |
|---|---|---|
| Customer Registration | Register | নিবন্ধন করুন |
| Login / Logout | Login / Logout | লগইন / লগআউট |
| Forgot Password (email reset) | Forgot Password | পাসওয়ার্ড ভুলে গেছেন |
| Email Verification | Verify Email | ইমেইল যাচাই করুন |
| Social Login (Google / Facebook) | Login with Google | গুগল দিয়ে লগইন |
| Profile Management | My Profile | আমার প্রোফাইল |
| Address Book (multiple addresses) | My Addresses | আমার ঠিকানা |
| Order History | My Orders | আমার অর্ডার |
| Admin / Staff Role Management | — | — |

---

## 3. Product Management

| Feature | EN | BN |
|---|---|---|
| Product listing with pagination | Products | পণ্যসমূহ |
| Product detail page | Product Details | পণ্যের বিবরণ |
| Product images (multiple, gallery) | — | — |
| Product variants (size, color, etc.) | Variants | ভেরিয়েন্ট |
| Product search | Search | অনুসন্ধান |
| Filter by category, price, brand | Filter | ফিল্টার |
| Sort by price, popularity, newest | Sort | সাজান |
| Related products | Related Products | সম্পর্কিত পণ্য |
| Featured / New Arrival / Sale badges | — | — |
| Product SKU & barcode | — | — |
| Digital / Physical product support | — | — |

**Model Fields:**
```
products: id, sku, price, sale_price, stock, is_active, featured
product_translations: product_id, locale, name, description, short_description
product_images: id, product_id, image_path, sort_order
product_variants: id, product_id, name, value, price_modifier, stock
```

---

## 4. Category & Inventory

| Feature | EN | BN |
|---|---|---|
| Hierarchical categories (parent/child) | Categories | বিভাগসমূহ |
| Category page with products | — | — |
| Brand / Manufacturer management | Brands | ব্র্যান্ড |
| Stock tracking (quantity) | In Stock | স্টকে আছে |
| Low stock alert (admin) | Low Stock Alert | কম স্টক সতর্কতা |
| Out of stock display | Out of Stock | স্টক শেষ |
| Stock reservation on checkout | — | — |

---

## 5. Shopping Cart & Wishlist

| Feature | EN | BN |
|---|---|---|
| Add / remove from cart | Add to Cart | কার্টে যোগ করুন |
| Update quantity in cart | Update Cart | কার্ট আপডেট করুন |
| Cart total & subtotal | Cart Total | কার্ট মোট |
| Guest cart (session-based) | — | — |
| Persistent cart (auth user) | — | — |
| Cart item count in header | — | — |
| Wishlist add / remove | Wishlist | উইশলিস্ট |
| Move from wishlist to cart | — | — |

---

## 6. Checkout & Order Management

| Feature | EN | BN |
|---|---|---|
| Single-page checkout | Checkout | চেকআউট |
| Billing & shipping address | Billing Address | বিলিং ঠিকানা |
| Shipping method selection | Shipping Method | শিপিং পদ্ধতি |
| Order summary before payment | Order Summary | অর্ডারের সারসংক্ষেপ |
| Order confirmation page | Order Confirmed | অর্ডার নিশ্চিত |
| Order confirmation email | — | — |
| Order tracking by order ID | Track Order | অর্ডার ট্র্যাক করুন |
| Order statuses | Pending / Processing / Shipped / Delivered / Cancelled | অপেক্ষমান / প্রক্রিয়াধীন / পাঠানো হয়েছে / ডেলিভারি হয়েছে / বাতিল |
| Invoice / receipt download (PDF) | Download Invoice | ইনভয়েস ডাউনলোড |
| Order cancellation (within window) | Cancel Order | অর্ডার বাতিল করুন |
| Return / Refund request | Return Request | রিটার্নের অনুরোধ |

---

## 7. Payment Gateway Integration

### 7.1 Manual Payments (bKash / Nagad)

**Flow:**

```
Customer selects bKash / Nagad
        ↓
System shows payment instructions:
  - Send amount to: 01XXXXXXXXX
  - Use "Send Money" option
  - Reference: Order #XXXX
        ↓
Customer submits Transaction ID (TrxID)
        ↓
Order placed with status: "Payment Pending Verification"
        ↓
Admin verifies TrxID manually in bKash/Nagad dashboard
        ↓
Admin marks payment as Verified → Order moves to "Processing"
        ↓
Customer notified (email / SMS)
```

| Feature | EN | BN |
|---|---|---|
| bKash manual payment option | Pay via bKash | বিকাশে পেমেন্ট করুন |
| Nagad manual payment option | Pay via Nagad | নগদে পেমেন্ট করুন |
| Payment instructions page | Payment Instructions | পেমেন্টের নির্দেশনা |
| TrxID submission form | Enter Transaction ID | ট্রানজেকশন আইডি দিন |
| Admin TrxID verification panel | Verify Payment | পেমেন্ট যাচাই করুন |
| Payment screenshot upload (optional) | Upload Screenshot | স্ক্রিনশট আপলোড করুন |
| Pending verification status badge | Pending Verification | যাচাই বাকি আছে |

**Database Schema:**

```sql
manual_payments (
    id, order_id, payment_method [bkash|nagad],
    sender_number, transaction_id, amount,
    screenshot_path, status [pending|verified|rejected],
    verified_by (admin_id), verified_at,
    rejection_reason, created_at, updated_at
)
```

**Configuration (config/payment.php):**

```php
'bkash' => [
    'merchant_number' => env('BKASH_MERCHANT_NUMBER', '01XXXXXXXXX'),
    'merchant_name'   => env('BKASH_MERCHANT_NAME', 'My Shop'),
    'account_type'    => 'Send Money', // or Agent
],
'nagad' => [
    'merchant_number' => env('NAGAD_MERCHANT_NUMBER', '01XXXXXXXXX'),
    'merchant_name'   => env('NAGAD_MERCHANT_NAME', 'My Shop'),
],
```

**ENV Variables:**

```env
BKASH_MERCHANT_NUMBER=01XXXXXXXXX
BKASH_MERCHANT_NAME="My Shop"
NAGAD_MERCHANT_NUMBER=01XXXXXXXXX
NAGAD_MERCHANT_NAME="My Shop"
```

---

### 7.2 SSL Commerz Integration

**Gateway:** [SSLCommerz](https://www.sslcommerz.com/) — Bangladesh's leading payment gateway.
**Supported Methods via SSLCommerz:** Visa, Mastercard, bKash (API), Nagad (API), Rocket, DBBL Nexus, Internet Banking, and more.

**Flow:**

```
Customer selects "Pay Online (SSLCommerz)"
        ↓
Backend creates SSLCommerz session (POST to SSLCommerz API)
        ↓
Customer redirected to SSLCommerz hosted payment page
        ↓
Customer completes payment on SSLCommerz
        ↓
SSLCommerz redirects to:
  - success_url  → verify IPN, update order to "Processing"
  - fail_url     → show failure, keep order "Pending"
  - cancel_url   → show cancellation message
        ↓
IPN (Instant Payment Notification) also hits /payment/ipn
  → double-verify val_id with SSLCommerz validation API
```

| Feature | EN | BN |
|---|---|---|
| Online payment via SSLCommerz | Pay Online | অনলাইনে পেমেন্ট করুন |
| Hosted payment page redirect | — | — |
| Payment success notification | Payment Successful | পেমেন্ট সফল হয়েছে |
| Payment failure message | Payment Failed | পেমেন্ট ব্যর্থ হয়েছে |
| IPN verification (webhook) | — | — |
| Order auto-update on success | — | — |
| Transaction log storage | — | — |
| Sandbox / Live mode toggle | — | — |

**Package:** `sslcommerz/sslcommerz-laravel` or custom implementation.

**ENV Variables:**

```env
SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCOMMERZ_IS_LIVE=false   # true for production
```

**Key Routes:**

```php
// routes/web.php
Route::prefix('payment')->group(function () {
    Route::post('/initiate',         [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::post('/success',          [PaymentController::class, 'success'])->name('payment.success');
    Route::post('/fail',             [PaymentController::class, 'fail'])->name('payment.fail');
    Route::post('/cancel',           [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('/ipn',              [PaymentController::class, 'ipn'])->name('payment.ipn');
    // Manual payment routes
    Route::post('/manual/submit',    [ManualPaymentController::class, 'submit'])->name('payment.manual.submit');
    Route::get('/manual/instructions/{order}', [ManualPaymentController::class, 'instructions'])->name('payment.manual.instructions');
});
```

**SSLCommerz Session Payload:**

```php
$postData = [
    'store_id'      => config('payment.sslcommerz.store_id'),
    'store_passwd'  => config('payment.sslcommerz.store_password'),
    'total_amount'  => $order->total_amount,
    'currency'      => 'BDT',
    'tran_id'       => $order->order_number,
    'success_url'   => route('payment.success'),
    'fail_url'      => route('payment.fail'),
    'cancel_url'    => route('payment.cancel'),
    'ipn_url'       => route('payment.ipn'),
    'cus_name'      => $order->customer_name,
    'cus_email'     => $order->customer_email,
    'cus_phone'     => $order->customer_phone,
    'cus_add1'      => $order->billing_address,
    'cus_city'      => $order->billing_city,
    'cus_country'   => 'Bangladesh',
    'shipping_method' => 'Courier',
    'product_name'  => 'E-Commerce Order',
    'product_category' => 'General',
    'product_profile' => 'general',
];
```

**Database Schema:**

```sql
payment_transactions (
    id, order_id, gateway [sslcommerz|bkash|nagad|cod],
    tran_id, val_id, amount, currency,
    card_type, bank_tran_id, status [pending|success|failed|cancelled],
    raw_response (JSON), created_at, updated_at
)
```

---

## 8. Coupon & Discount System

| Feature | EN | BN |
|---|---|---|
| Coupon code at checkout | Apply Coupon | কুপন প্রয়োগ করুন |
| Fixed amount discount | — | — |
| Percentage discount | — | — |
| Minimum order amount condition | — | — |
| Usage limit per coupon | — | — |
| Usage limit per user | — | — |
| Coupon expiry date | — | — |
| Product / category specific coupons | — | — |
| Flash sale / time-limited price | Flash Sale | ফ্ল্যাশ সেল |
| Bulk pricing (qty-based discount) | — | — |

---

## 9. Reviews & Ratings

| Feature | EN | BN |
|---|---|---|
| Star rating (1–5) | Rate Product | পণ্য রেট করুন |
| Text review submission | Write a Review | রিভিউ লিখুন |
| Review after purchase only (optional) | Verified Purchase | যাচাইকৃত ক্রয় |
| Admin review moderation | — | — |
| Average rating display | — | — |
| Helpful votes on reviews | Helpful | সহায়ক |

---

## 10. Shipping & Delivery

| Feature | EN | BN |
|---|---|---|
| Flat rate shipping | Flat Rate | নির্ধারিত ডেলিভারি চার্জ |
| Free shipping above threshold | Free Shipping | বিনামূল্যে ডেলিভারি |
| Location-based shipping rates | — | — |
| District / Upazila delivery zones | — | — |
| Cash on Delivery (COD) | Cash on Delivery | ক্যাশ অন ডেলিভারি |
| Delivery time estimate | Estimated Delivery | আনুমানিক ডেলিভারি সময় |
| Courier tracking number input (admin) | Tracking Number | ট্র্যাকিং নম্বর |

---

## 11. Admin Panel

| Feature | EN | BN |
|---|---|---|
| Dashboard with sales summary | Dashboard | ড্যাশবোর্ড |
| Order management | Orders | অর্ডারসমূহ |
| Product CRUD | Products | পণ্যসমূহ |
| Category CRUD | Categories | বিভাগসমূহ |
| Customer list & details | Customers | গ্রাহকসমূহ |
| Manual payment verification | Verify Payments | পেমেন্ট যাচাই |
| Coupon management | Coupons | কুপন |
| Shipping zone management | Shipping Zones | শিপিং জোন |
| Review moderation | Reviews | রিভিউ |
| Report: sales, revenue, orders | Reports | প্রতিবেদন |
| Settings (store name, logo, contacts) | Settings | সেটিংস |
| Language content management | Translations | অনুবাদ |
| Banner / slider management | Banners | ব্যানার |
| Staff roles & permissions | Staff | স্টাফ |

---

## 12. Notification System

| Feature | EN | BN |
|---|---|---|
| Order placed email | Order Confirmation | অর্ডার নিশ্চিতকরণ |
| Payment verified email | Payment Confirmed | পেমেন্ট নিশ্চিত হয়েছে |
| Order shipped email (with tracking) | Order Shipped | অর্ডার পাঠানো হয়েছে |
| Order delivered email | Order Delivered | ডেলিভারি সম্পন্ন |
| Low stock alert (admin) | Low Stock | কম স্টক |
| New order alert (admin) | New Order | নতুন অর্ডার |
| SMS notification (optional, via SMS API) | — | — |
| In-app notification (database) | Notifications | নোটিফিকেশন |

---

## 13. SEO & Performance

| Feature | Description |
|---|---|
| SEO meta title & description per product/category | Editable in admin |
| Open Graph tags for social sharing | Automatic from product data |
| Canonical URL | Laravel route model binding |
| Sitemap generation | `spatie/laravel-sitemap` |
| Image lazy loading | Frontend implementation |
| Product image optimization | Store compressed, serve WebP |
| Page caching | Laravel cache + Redis |
| Route caching | `php artisan route:cache` |

---

## 14. Security

| Feature | Description |
|---|---|
| CSRF protection | Laravel built-in on all forms |
| SQL injection prevention | Eloquent ORM / parameterized queries |
| XSS prevention | Blade `{{ }}` auto-escaping |
| Input validation | Laravel Form Requests |
| Rate limiting | `ThrottleRequests` middleware on login, checkout, payment |
| IPN signature verification | SSLCommerz `val_id` re-validation via API |
| Manual payment fraud prevention | Admin must verify each TrxID before fulfillment |
| HTTPS enforcement | `ForceHttps` middleware in production |
| Sensitive data encryption | `.env` based secrets, never hardcoded |
| Role-based access control (RBAC) | `spatie/laravel-permission` |

---

## Payment Method Summary Table

| Method | Type | Currency | Auto-Verify | Manual Steps |
|---|---|---|---|---|
| bKash (Send Money) | Manual | BDT | No | Customer submits TrxID → Admin verifies |
| Nagad (Send Money) | Manual | BDT | No | Customer submits TrxID → Admin verifies |
| SSLCommerz (Card/MFS/Bank) | Automated | BDT | Yes (IPN) | None after gateway approval |
| Cash on Delivery | Manual | BDT | No | Collected by delivery agent |

---

## Environment Variables Reference

```env
# App
APP_NAME="My Shop"
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# SSLCommerz
SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCOMMERZ_IS_LIVE=false

# bKash Manual
BKASH_MERCHANT_NUMBER=01XXXXXXXXX
BKASH_MERCHANT_NAME="My Shop"

# Nagad Manual
NAGAD_MERCHANT_NUMBER=01XXXXXXXXX
NAGAD_MERCHANT_NAME="My Shop"

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_FROM_ADDRESS="noreply@myshop.com"
MAIL_FROM_NAME="My Shop"
```

---

## Suggested Package Dependencies

```json
{
    "require": {
        "spatie/laravel-translatable": "^6.0",
        "spatie/laravel-permission": "^6.0",
        "spatie/laravel-sitemap": "^7.0",
        "barryvdh/laravel-dompdf": "^2.0",
        "intervention/image": "^3.0"
    }
}
```

---

*Document maintained in `/FEATURES.md` at project root.*
