<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── General ───────────────────────────────────────────────────
            ['group' => 'general', 'key' => 'site_name',           'value' => 'ecom-tech',                     'is_public' => true],
            ['group' => 'general', 'key' => 'site_name_bn',        'value' => 'ই-কম-টেক',                     'is_public' => true],
            ['group' => 'general', 'key' => 'site_tagline',        'value' => 'Your Trusted Online Shop',      'is_public' => true],
            ['group' => 'general', 'key' => 'site_tagline_bn',     'value' => 'আপনার বিশ্বস্ত অনলাইন শপ',    'is_public' => true],
            ['group' => 'general', 'key' => 'site_logo',           'value' => 'images/logo.png',               'is_public' => true],
            ['group' => 'general', 'key' => 'site_favicon',        'value' => 'images/favicon.ico',            'is_public' => true],
            ['group' => 'general', 'key' => 'site_email',          'value' => 'support@ecom.test',             'is_public' => true],
            ['group' => 'general', 'key' => 'site_phone',          'value' => '01700-000000',                  'is_public' => true],
            ['group' => 'general', 'key' => 'site_address',        'value' => 'House 1, Road 1, Dhaka 1200',   'is_public' => true],
            ['group' => 'general', 'key' => 'site_currency',       'value' => 'BDT',                           'is_public' => true],
            ['group' => 'general', 'key' => 'site_currency_symbol','value' => '৳',                             'is_public' => true],
            ['group' => 'general', 'key' => 'default_locale',      'value' => 'en',                            'is_public' => true],
            ['group' => 'general', 'key' => 'supported_locales',   'value' => 'en,bn',                         'is_public' => true],
            ['group' => 'general', 'key' => 'maintenance_mode',    'value' => '0',                             'is_public' => false],
            ['group' => 'general', 'key' => 'per_page_products',   'value' => '24',                            'is_public' => true],

            // ── SEO ───────────────────────────────────────────────────────
            ['group' => 'seo', 'key' => 'meta_title',        'value' => 'ecom-tech — Best Online Shopping Bangladesh',                              'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_description',  'value' => 'Shop electronics, fashion, groceries & more at best prices in Bangladesh.', 'is_public' => true],
            ['group' => 'seo', 'key' => 'og_image',          'value' => 'images/og-image.jpg',                                                      'is_public' => true],
            ['group' => 'seo', 'key' => 'google_analytics',  'value' => '',                                                                          'is_public' => false],

            // ── Payment ───────────────────────────────────────────────────
            ['group' => 'payment', 'key' => 'cod_enabled',              'value' => '1',               'is_public' => true],
            ['group' => 'payment', 'key' => 'bkash_enabled',            'value' => '1',               'is_public' => true],
            ['group' => 'payment', 'key' => 'bkash_merchant_number',    'value' => '01XXXXXXXXX',     'is_public' => true],
            ['group' => 'payment', 'key' => 'bkash_merchant_name',      'value' => 'ecom-tech',       'is_public' => true],
            ['group' => 'payment', 'key' => 'nagad_enabled',            'value' => '1',               'is_public' => true],
            ['group' => 'payment', 'key' => 'nagad_merchant_number',    'value' => '01XXXXXXXXX',     'is_public' => true],
            ['group' => 'payment', 'key' => 'nagad_merchant_name',      'value' => 'ecom-tech',       'is_public' => true],
            ['group' => 'payment', 'key' => 'sslcommerz_enabled',       'value' => '1',               'is_public' => true],
            ['group' => 'payment', 'key' => 'sslcommerz_is_live',       'value' => '0',               'is_public' => false],
            ['group' => 'payment', 'key' => 'sslcommerz_store_id',      'value' => '',                'is_public' => false],
            ['group' => 'payment', 'key' => 'sslcommerz_store_password','value' => '',                'is_public' => false],

            // ── Shipping ──────────────────────────────────────────────────
            ['group' => 'shipping', 'key' => 'free_shipping_enabled',   'value' => '1',    'is_public' => true],
            ['group' => 'shipping', 'key' => 'free_shipping_min_order', 'value' => '1000', 'is_public' => true],

            // ── Mail ──────────────────────────────────────────────────────
            ['group' => 'mail', 'key' => 'mail_from_address',  'value' => 'noreply@ecom.test', 'is_public' => false],
            ['group' => 'mail', 'key' => 'mail_from_name',     'value' => 'ecom-tech',         'is_public' => false],
            ['group' => 'mail', 'key' => 'order_confirm_mail', 'value' => '1',                 'is_public' => false],
            ['group' => 'mail', 'key' => 'ship_notify_mail',   'value' => '1',                 'is_public' => false],

            // ── Social links ──────────────────────────────────────────────
            ['group' => 'social', 'key' => 'facebook_url',    'value' => 'https://facebook.com/ecomtech',  'is_public' => true],
            ['group' => 'social', 'key' => 'instagram_url',   'value' => 'https://instagram.com/ecomtech', 'is_public' => true],
            ['group' => 'social', 'key' => 'youtube_url',     'value' => '',                               'is_public' => true],
            ['group' => 'social', 'key' => 'whatsapp_number', 'value' => '01700-000000',                   'is_public' => true],

            // ── Order ─────────────────────────────────────────────────────
            ['group' => 'order', 'key' => 'min_order_amount',         'value' => '100',  'is_public' => true],
            ['group' => 'order', 'key' => 'cancellation_window_hours','value' => '24',   'is_public' => true],
            ['group' => 'order', 'key' => 'return_window_days',       'value' => '7',    'is_public' => true],
            ['group' => 'order', 'key' => 'low_stock_alert_admin',    'value' => '1',    'is_public' => false],

            // ── Homepage content ──────────────────────────────────────────
            ['group' => 'homepage', 'key' => 'hero_banner_title',    'value' => 'Shop Smart, Shop Bangladesh',    'is_public' => true],
            ['group' => 'homepage', 'key' => 'hero_banner_title_bn', 'value' => 'স্মার্টভাবে কিনুন, বাংলাদেশে কিনুন', 'is_public' => true],
            ['group' => 'homepage', 'key' => 'hero_banner_subtitle', 'value' => 'Best prices on electronics, fashion, groceries & more.', 'is_public' => true],
            ['group' => 'homepage', 'key' => 'show_featured_products','value' => '1', 'is_public' => true],
            ['group' => 'homepage', 'key' => 'featured_products_count','value' => '8', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('  Settings seeded (' . count($settings) . ' settings).');
    }
}
