<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: find category by EN slug
        $cat = fn(string $slug) => CategoryTranslation::where('locale', 'en')
            ->where('slug', $slug)
            ->value('category_id');

        $brand = fn(string $slug) => Brand::where('slug', $slug)->value('id');

        $products = [
            // ── Mobile Phones ────────────────────────────────────────────────
            [
                'category_slug' => 'mobile-phones',
                'brand_slug'    => 'samsung',
                'sku'           => 'SAM-S24U-001',
                'price'         => 119990.00,
                'sale_price'    => 109990.00,
                'stock'         => 45,
                'weight'        => 0.233,
                'is_featured'   => true,
                'translations'  => [
                    'en' => [
                        'name'              => 'Samsung Galaxy S24 Ultra',
                        'slug'              => 'samsung-galaxy-s24-ultra',
                        'short_description' => '6.8" Dynamic AMOLED, Snapdragon 8 Gen 3, 200MP camera, S Pen included.',
                        'description'       => 'The Samsung Galaxy S24 Ultra is the pinnacle of smartphone technology featuring a 6.8-inch Dynamic AMOLED 2X display with 2600 nits peak brightness. Powered by the Snapdragon 8 Gen 3 processor and backed by 12GB RAM. The 200MP main camera captures extraordinary detail. The integrated S Pen makes creativity limitless. With a 5000mAh battery and 45W fast charging, it keeps you powered all day.',
                        'meta_title'        => 'Samsung Galaxy S24 Ultra Price in Bangladesh',
                        'meta_description'  => 'Buy Samsung Galaxy S24 Ultra online at best price in Bangladesh. 200MP camera, S Pen, 6.8 AMOLED.',
                    ],
                    'bn' => [
                        'name'              => 'স্যামসাং গ্যালাক্সি এস২৪ আলট্রা',
                        'slug'              => 'samsung-galaxy-s24-ultra-bn',
                        'short_description' => '৬.৮" ডায়নামিক অ্যামোলেড, স্ন্যাপড্রাগন ৮ জেন ৩, ২০০এমপি ক্যামেরা, এস পেন সহ।',
                        'description'       => 'স্যামসাং গ্যালাক্সি এস২৪ আলট্রা স্মার্টফোন প্রযুক্তির শীর্ষ। ৬.৮ ইঞ্চি ডায়নামিক অ্যামোলেড ডিসপ্লে, স্ন্যাপড্রাগন ৮ জেন ৩ প্রসেসর এবং ২০০ মেগাপিক্সেল ক্যামেরা দিয়ে এটি একটি অসাধারণ ডিভাইস।',
                        'meta_title'        => 'বাংলাদেশে স্যামসাং গ্যালাক্সি এস২৪ আলট্রার দাম',
                        'meta_description'  => 'বাংলাদেশে সেরা দামে স্যামসাং গ্যালাক্সি এস২৪ আলট্রা কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'SAM-S24U-256', 'price_modifier' => 0,       'stock' => 20, 'options' => [['Storage', '256GB'], ['Color', 'Titanium Black']]],
                    ['sku' => 'SAM-S24U-512', 'price_modifier' => 10000.00, 'stock' => 15, 'options' => [['Storage', '512GB'], ['Color', 'Titanium Gray']]],
                    ['sku' => 'SAM-S24U-1TB', 'price_modifier' => 20000.00, 'stock' => 10, 'options' => [['Storage', '1TB'],   ['Color', 'Titanium Violet']]],
                ],
            ],
            [
                'category_slug' => 'mobile-phones',
                'brand_slug'    => 'apple',
                'sku'           => 'APL-IP15P-001',
                'price'         => 154990.00,
                'sale_price'    => null,
                'stock'         => 30,
                'weight'        => 0.221,
                'is_featured'   => true,
                'translations'  => [
                    'en' => [
                        'name'              => 'Apple iPhone 15 Pro',
                        'slug'              => 'apple-iphone-15-pro',
                        'short_description' => 'A17 Pro chip, titanium design, 48MP main camera, Action Button.',
                        'description'       => 'iPhone 15 Pro features the A17 Pro chip — the most powerful chip ever in a smartphone. The aerospace-grade titanium design makes it incredibly light and strong. The 48MP main camera system with 3x optical zoom captures stunning photos in any light. The Action Button is fully customizable to your most-used feature.',
                        'meta_title'        => 'Apple iPhone 15 Pro Price in Bangladesh',
                        'meta_description'  => 'Buy Apple iPhone 15 Pro in Bangladesh. A17 Pro chip, titanium design, 48MP camera.',
                    ],
                    'bn' => [
                        'name'              => 'অ্যাপল আইফোন ১৫ প্রো',
                        'slug'              => 'apple-iphone-15-pro-bn',
                        'short_description' => 'এ১৭ প্রো চিপ, টাইটানিয়াম ডিজাইন, ৪৮এমপি মেইন ক্যামেরা।',
                        'description'       => 'আইফোন ১৫ প্রোতে রয়েছে এ১৭ প্রো চিপ — যা এখন পর্যন্ত স্মার্টফোনে সবচেয়ে শক্তিশালী চিপ। অ্যারোস্পেস-গ্রেড টাইটানিয়াম ডিজাইন এটিকে অবিশ্বাস্যভাবে হালকা এবং শক্তিশালী করে তোলে।',
                        'meta_title'        => 'বাংলাদেশে অ্যাপল আইফোন ১৫ প্রোর দাম',
                        'meta_description'  => 'বাংলাদেশে অ্যাপল আইফোন ১৫ প্রো কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'APL-IP15P-128', 'price_modifier' => 0,       'stock' => 10, 'options' => [['Storage', '128GB'], ['Color', 'Natural Titanium']]],
                    ['sku' => 'APL-IP15P-256', 'price_modifier' => 10000.00, 'stock' => 12, 'options' => [['Storage', '256GB'], ['Color', 'Blue Titanium']]],
                    ['sku' => 'APL-IP15P-512', 'price_modifier' => 25000.00, 'stock' => 8,  'options' => [['Storage', '512GB'], ['Color', 'Black Titanium']]],
                ],
            ],

            // ── Laptops ───────────────────────────────────────────────────────
            [
                'category_slug' => 'laptops-computers',
                'brand_slug'    => 'samsung',
                'sku'           => 'SAM-BOOK3-001',
                'price'         => 85000.00,
                'sale_price'    => 79000.00,
                'stock'         => 20,
                'weight'        => 1.590,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'Samsung Galaxy Book3 Pro 360',
                        'slug'              => 'samsung-galaxy-book3-pro-360',
                        'short_description' => '13th Gen Intel Core i7, 16GB RAM, 512GB SSD, 13.3" AMOLED touchscreen.',
                        'description'       => 'The Galaxy Book3 Pro 360 is a 2-in-1 laptop with a stunning 13.3-inch AMOLED touchscreen. Powered by the 13th Gen Intel Core i7 with 16GB LPDDR5 RAM and a 512GB NVMe SSD, it handles demanding tasks with ease. The 360-degree hinge lets you switch between laptop and tablet modes seamlessly.',
                        'meta_title'        => 'Samsung Galaxy Book3 Pro 360 Price in Bangladesh',
                        'meta_description'  => 'Buy Samsung Galaxy Book3 Pro 360 laptop in Bangladesh.',
                    ],
                    'bn' => [
                        'name'              => 'স্যামসাং গ্যালাক্সি বুক৩ প্রো ৩৬০',
                        'slug'              => 'samsung-galaxy-book3-pro-360-bn',
                        'short_description' => '১৩তম জেন ইন্টেল কোর আই৭, ১৬জিবি র‌্যাম, ৫১২জিবি এসএসডি।',
                        'description'       => 'গ্যালাক্সি বুক৩ প্রো ৩৬০ একটি টু-ইন-ওয়ান ল্যাপটপ যেখানে রয়েছে চমৎকার ১৩.৩ ইঞ্চি অ্যামোলেড টাচস্ক্রিন।',
                        'meta_title'        => 'বাংলাদেশে স্যামসাং গ্যালাক্সি বুক৩ প্রো ৩৬০ এর দাম',
                        'meta_description'  => 'বাংলাদেশে স্যামসাং গ্যালাক্সি বুক৩ ল্যাপটপ কিনুন।',
                    ],
                ],
                'variants' => [],
            ],

            // ── Televisions ───────────────────────────────────────────────────
            [
                'category_slug' => 'televisions',
                'brand_slug'    => 'walton',
                'sku'           => 'WAL-TV55-4K-001',
                'price'         => 55000.00,
                'sale_price'    => 49900.00,
                'stock'         => 15,
                'weight'        => 12.500,
                'is_featured'   => true,
                'translations'  => [
                    'en' => [
                        'name'              => 'Walton 55" 4K Smart LED TV',
                        'slug'              => 'walton-55-4k-smart-led-tv',
                        'short_description' => '55-inch 4K UHD Smart Android TV with HDR10, Dolby Audio, and built-in Wi-Fi.',
                        'description'       => 'Experience stunning 4K visuals on this 55-inch Walton Smart Android TV. HDR10 technology delivers vibrant colors and deep contrast. With Android OS built-in, access Netflix, YouTube, and all your favorite streaming apps. Dolby Audio provides a rich, immersive sound experience. Built-in Wi-Fi and Bluetooth for seamless connectivity.',
                        'meta_title'        => 'Walton 55 inch 4K Smart TV Price Bangladesh',
                        'meta_description'  => 'Buy Walton 55 inch 4K Smart Android LED TV at best price in Bangladesh.',
                    ],
                    'bn' => [
                        'name'              => 'ওয়ালটন ৫৫" ৪কে স্মার্ট এলইডি টিভি',
                        'slug'              => 'walton-55-4k-smart-led-tv-bn',
                        'short_description' => '৫৫ ইঞ্চি ৪কে ইউএইচডি স্মার্ট অ্যান্ড্রয়েড টিভি, এইচডিআর১০, ডলবি অডিও।',
                        'description'       => 'এই ৫৫ ইঞ্চি ওয়ালটন স্মার্ট অ্যান্ড্রয়েড টিভিতে অসাধারণ ৪কে ভিজ্যুয়াল উপভোগ করুন। এইচডিআর১০ প্রযুক্তি উজ্জ্বল রঙ এবং গভীর কনট্রাস্ট প্রদান করে।',
                        'meta_title'        => 'বাংলাদেশে ওয়ালটন ৫৫ ইঞ্চি ৪কে স্মার্ট টিভির দাম',
                        'meta_description'  => 'বাংলাদেশে সেরা দামে ওয়ালটন স্মার্ট এলইডি টিভি কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'WAL-TV43-4K', 'price_modifier' => -12000.00, 'stock' => 10, 'options' => [['Screen Size', '43 inch']]],
                    ['sku' => 'WAL-TV55-4K', 'price_modifier' => 0,         'stock' => 15, 'options' => [['Screen Size', '55 inch']]],
                    ['sku' => 'WAL-TV65-4K', 'price_modifier' => 15000.00,  'stock' => 5,  'options' => [['Screen Size', '65 inch']]],
                ],
            ],

            // ── Men's Clothing ────────────────────────────────────────────────
            [
                'category_slug' => 'mens-clothing',
                'brand_slug'    => null,
                'sku'           => 'MEN-POLO-001',
                'price'         => 850.00,
                'sale_price'    => 699.00,
                'stock'         => 200,
                'weight'        => 0.250,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'Premium Cotton Polo Shirt',
                        'slug'              => 'premium-cotton-polo-shirt',
                        'short_description' => '100% premium cotton polo shirt, breathable fabric, available in multiple colors and sizes.',
                        'description'       => 'Crafted from 100% premium combed cotton, this polo shirt offers exceptional comfort and durability. The breathable fabric keeps you cool in warm weather. Features a 3-button placket, ribbed collar and cuffs, and a small embroidered logo. Machine washable and retains shape after multiple washes.',
                        'meta_title'        => 'Premium Cotton Polo Shirt for Men',
                        'meta_description'  => 'Buy premium cotton polo shirt for men at best price in Bangladesh. Multiple colors and sizes available.',
                    ],
                    'bn' => [
                        'name'              => 'প্রিমিয়াম কটন পোলো শার্ট',
                        'slug'              => 'premium-cotton-polo-shirt-bn',
                        'short_description' => '১০০% প্রিমিয়াম কটন পোলো শার্ট, আরামদায়ক ফ্যাব্রিক, একাধিক রং ও সাইজে পাওয়া যায়।',
                        'description'       => '১০০% প্রিমিয়াম কম্বড কটন থেকে তৈরি এই পোলো শার্ট অসাধারণ আরাম এবং টেকসই প্রদান করে। শ্বাসযোগ্য ফ্যাব্রিক গরম আবহাওয়ায় আপনাকে ঠান্ডা রাখে।',
                        'meta_title'        => 'পুরুষদের জন্য প্রিমিয়াম কটন পোলো শার্ট',
                        'meta_description'  => 'বাংলাদেশে সেরা দামে পুরুষদের প্রিমিয়াম কটন পোলো শার্ট কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'POLO-S-WHT',  'price_modifier' => 0, 'stock' => 30, 'options' => [['Size', 'S'],  ['Color', 'White']]],
                    ['sku' => 'POLO-M-WHT',  'price_modifier' => 0, 'stock' => 50, 'options' => [['Size', 'M'],  ['Color', 'White']]],
                    ['sku' => 'POLO-L-WHT',  'price_modifier' => 0, 'stock' => 50, 'options' => [['Size', 'L'],  ['Color', 'White']]],
                    ['sku' => 'POLO-XL-WHT', 'price_modifier' => 0, 'stock' => 40, 'options' => [['Size', 'XL'], ['Color', 'White']]],
                    ['sku' => 'POLO-M-BLK',  'price_modifier' => 0, 'stock' => 30, 'options' => [['Size', 'M'],  ['Color', 'Black']]],
                    ['sku' => 'POLO-L-BLK',  'price_modifier' => 0, 'stock' => 30, 'options' => [['Size', 'L'],  ['Color', 'Black']]],
                ],
            ],

            // ── Home Appliances ───────────────────────────────────────────────
            [
                'category_slug' => 'home-appliances',
                'brand_slug'    => 'walton',
                'sku'           => 'WAL-REF-257-001',
                'price'         => 38000.00,
                'sale_price'    => 34900.00,
                'stock'         => 12,
                'weight'        => 48.000,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'Walton 257L Frost Free Refrigerator',
                        'slug'              => 'walton-257l-frost-free-refrigerator',
                        'short_description' => '257-litre double-door frost-free refrigerator with inverter compressor, energy-saving A++ rating.',
                        'description'       => 'This Walton frost-free refrigerator offers 257 litres of total capacity across two spacious compartments. The inverter compressor automatically adjusts speed based on cooling needs, saving up to 40% energy. No manual defrosting required. Features a digital display, multi-airflow system, and deodoriser for fresh food storage.',
                        'meta_title'        => 'Walton 257L Double Door Refrigerator Price Bangladesh',
                        'meta_description'  => 'Buy Walton 257L Frost Free Refrigerator at best price in Bangladesh.',
                    ],
                    'bn' => [
                        'name'              => 'ওয়ালটন ২৫৭ লিটার ফ্রস্ট ফ্রি রেফ্রিজারেটর',
                        'slug'              => 'walton-257l-frost-free-refrigerator-bn',
                        'short_description' => '২৫৭ লিটার ডাবল ডোর ফ্রস্ট ফ্রি রেফ্রিজারেটর, ইনভার্টার কম্প্রেসর, এ++ এনার্জি রেটিং।',
                        'description'       => 'এই ওয়ালটন ফ্রস্ট ফ্রি রেফ্রিজারেটর দুটি প্রশস্ত কম্পার্টমেন্টে মোট ২৫৭ লিটার ধারণক্ষমতা প্রদান করে।',
                        'meta_title'        => 'বাংলাদেশে ওয়ালটন ২৫৭ লিটার রেফ্রিজারেটরের দাম',
                        'meta_description'  => 'বাংলাদেশে সেরা দামে ওয়ালটন ফ্রস্ট ফ্রি রেফ্রিজারেটর কিনুন।',
                    ],
                ],
                'variants' => [],
            ],

            // ── Grocery ───────────────────────────────────────────────────────
            [
                'category_slug' => 'rice-grains',
                'brand_slug'    => 'pran',
                'sku'           => 'PRAN-RICE-5KG-001',
                'price'         => 380.00,
                'sale_price'    => 350.00,
                'stock'         => 500,
                'weight'        => 5.000,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'PRAN Miniket Rice 5KG',
                        'slug'              => 'pran-miniket-rice-5kg',
                        'short_description' => 'Premium quality Miniket rice, 5KG pack. Aromatic, fine-grain, perfect for everyday cooking.',
                        'description'       => 'PRAN Miniket Rice is sourced from the finest rice-growing regions of Bangladesh. The fine, long grains cook to a fluffy, non-sticky texture with a natural aroma. Free from artificial additives. Ideal for daily meals, biriyani, and special occasions. 5KG pack in a moisture-proof bag.',
                        'meta_title'        => 'PRAN Miniket Rice 5KG Price Bangladesh',
                        'meta_description'  => 'Buy PRAN Miniket Rice 5KG online at best price in Bangladesh.',
                    ],
                    'bn' => [
                        'name'              => 'প্রাণ মিনিকেট চাল ৫ কেজি',
                        'slug'              => 'pran-miniket-rice-5kg-bn',
                        'short_description' => 'প্রিমিয়াম মানের মিনিকেট চাল, ৫ কেজি প্যাক। সুগন্ধি, সূক্ষ্ম দানা।',
                        'description'       => 'প্রাণ মিনিকেট চাল বাংলাদেশের সেরা চাল উৎপাদন অঞ্চল থেকে সংগ্রহ করা হয়। সূক্ষ্ম, লম্বা দানা রান্না করলে ঝরঝরে, আঠালোমুক্ত এবং প্রাকৃতিক সুগন্ধে পরিণত হয়।',
                        'meta_title'        => 'বাংলাদেশে প্রাণ মিনিকেট চাল ৫ কেজির দাম',
                        'meta_description'  => 'বাংলাদেশে সেরা দামে অনলাইনে প্রাণ মিনিকেট চাল কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'PRAN-RICE-5KG',  'price_modifier' => 0,      'stock' => 300, 'options' => [['Pack Size', '5 KG']]],
                    ['sku' => 'PRAN-RICE-10KG', 'price_modifier' => 320.00, 'stock' => 150, 'options' => [['Pack Size', '10 KG']]],
                    ['sku' => 'PRAN-RICE-25KG', 'price_modifier' => 870.00, 'stock' => 50,  'options' => [['Pack Size', '25 KG']]],
                ],
            ],

            // ── Skincare ──────────────────────────────────────────────────────
            [
                'category_slug' => 'skincare',
                'brand_slug'    => 'unilever',
                'sku'           => 'UNI-POND-CREAM-001',
                'price'         => 295.00,
                'sale_price'    => 260.00,
                'stock'         => 300,
                'weight'        => 0.100,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'Pond\'s White Beauty Day Cream SPF 15',
                        'slug'              => 'ponds-white-beauty-day-cream-spf15',
                        'short_description' => 'Pond\'s White Beauty Spot-less Fairness Cream with SPF 15. 50g jar.',
                        'description'       => 'Pond\'s White Beauty Day Cream with SPF 15 provides daily sun protection while brightening skin tone. The formula contains Vitamin B3+ and a special pink tint for a radiant glow. Lightweight, non-greasy texture absorbs quickly. Dermatologically tested. 50g jar suitable for normal to oily skin.',
                        'meta_title'        => 'Ponds White Beauty Cream Price in Bangladesh',
                        'meta_description'  => 'Buy Ponds White Beauty Day Cream SPF 15 online in Bangladesh.',
                    ],
                    'bn' => [
                        'name'              => 'পন্ডস হোয়াইট বিউটি ডে ক্রিম এসপিএফ ১৫',
                        'slug'              => 'ponds-white-beauty-day-cream-spf15-bn',
                        'short_description' => 'পন্ডস হোয়াইট বিউটি স্পট-লেস ফেয়ারনেস ক্রিম, এসপিএফ ১৫ সহ। ৫০ গ্রাম।',
                        'description'       => 'পন্ডস হোয়াইট বিউটি ডে ক্রিম এসপিএফ ১৫ সহ প্রতিদিনের সূর্য সুরক্ষা প্রদান করে এবং ত্বকের রঙ উজ্জ্বল করে।',
                        'meta_title'        => 'বাংলাদেশে পন্ডস হোয়াইট বিউটি ক্রিমের দাম',
                        'meta_description'  => 'বাংলাদেশে পন্ডস হোয়াইট বিউটি ক্রিম কিনুন।',
                    ],
                ],
                'variants' => [
                    ['sku' => 'POND-CREAM-35G', 'price_modifier' => -75.00, 'stock' => 120, 'options' => [['Size', '35g']]],
                    ['sku' => 'POND-CREAM-50G', 'price_modifier' => 0,      'stock' => 150, 'options' => [['Size', '50g']]],
                    ['sku' => 'POND-CREAM-75G', 'price_modifier' => 80.00,  'stock' => 80,  'options' => [['Size', '75g']]],
                ],
            ],

            // ── Bangla Books ──────────────────────────────────────────────────
            [
                'category_slug' => 'bangla-books',
                'brand_slug'    => null,
                'sku'           => 'BOOK-RBAG-001',
                'price'         => 400.00,
                'sale_price'    => 340.00,
                'stock'         => 80,
                'weight'        => 0.380,
                'is_featured'   => false,
                'translations'  => [
                    'en' => [
                        'name'              => 'Feluda Samagra (Complete Collection)',
                        'slug'              => 'feluda-samagra-complete-collection',
                        'short_description' => 'Complete collection of Feluda detective stories by Satyajit Ray. Hardcover.',
                        'description'       => 'Feluda Samagra is the complete collection of all Feluda detective stories written by the legendary Satyajit Ray. This hardcover edition contains all 35 Feluda adventures in a single volume. Feluda — real name Pradosh Chandra Mitter — is one of Bengali literature\'s most beloved fictional detectives. Essential for any book lover.',
                        'meta_title'        => 'Feluda Samagra Complete Collection Price Bangladesh',
                        'meta_description'  => 'Buy Feluda Samagra complete Satyajit Ray collection online at best price.',
                    ],
                    'bn' => [
                        'name'              => 'ফেলুদা সমগ্র (সম্পূর্ণ সংকলন)',
                        'slug'              => 'feluda-samagra-complete-collection-bn',
                        'short_description' => 'সত্যজিৎ রায় রচিত ফেলুদার সমস্ত গোয়েন্দা কাহিনির সম্পূর্ণ সংকলন।',
                        'description'       => 'ফেলুদা সমগ্র হল কিংবদন্তি সত্যজিৎ রায়ের লেখা সমস্ত ফেলুদা গোয়েন্দা গল্পের সম্পূর্ণ সংকলন। এই হার্ডকভার সংস্করণে একটি ভলিউমে সমস্ত ৩৫টি ফেলুদা অ্যাডভেঞ্চার রয়েছে।',
                        'meta_title'        => 'বাংলাদেশে ফেলুদা সমগ্রের দাম',
                        'meta_description'  => 'সেরা দামে ফেলুদা সমগ্র সম্পূর্ণ সংকলন কিনুন।',
                    ],
                ],
                'variants' => [],
            ],
        ];

        foreach ($products as $data) {
            $categoryId = $cat($data['category_slug']);
            $brandId    = $data['brand_slug'] ? $brand($data['brand_slug']) : null;

            if (!$categoryId) continue;

            // Create product if not exists
            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id'         => $categoryId,
                    'brand_id'            => $brandId,
                    'price'               => $data['price'],
                    'sale_price'          => $data['sale_price'],
                    'stock'               => $data['stock'],
                    'weight'              => $data['weight'],
                    'is_active'           => true,
                    'is_featured'         => $data['is_featured'],
                    'low_stock_threshold' => 5,
                ]
            );

            // Translations
            foreach ($data['translations'] as $locale => $trans) {
                ProductTranslation::firstOrCreate(
                    ['product_id' => $product->id, 'locale' => $locale],
                    $trans
                );
            }

            // Placeholder primary image
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'sort_order' => 0],
                [
                    'path'       => "products/{$product->sku}/main.jpg",
                    'alt_text'   => $data['translations']['en']['name'],
                    'is_primary' => true,
                ]
            );

            // Variants
            foreach ($data['variants'] as $varData) {
                $variant = ProductVariant::firstOrCreate(
                    ['sku' => $varData['sku']],
                    [
                        'product_id'     => $product->id,
                        'price_modifier' => $varData['price_modifier'],
                        'stock'          => $varData['stock'],
                        'is_active'      => true,
                    ]
                );

                foreach ($varData['options'] as [$optName, $optValue]) {
                    ProductVariantOption::firstOrCreate(
                        ['variant_id' => $variant->id, 'option_name' => $optName],
                        ['option_value' => $optValue]
                    );
                }
            }
        }

        $this->command->info('  Products seeded.');
    }
}
