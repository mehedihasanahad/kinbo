<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Seeder;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ── Brands ────────────────────────────────────────────────────────────
        $brands = [
            ['name' => 'Samsung',    'slug' => 'samsung'],
            ['name' => 'Apple',      'slug' => 'apple'],
            ['name' => 'Sony',       'slug' => 'sony'],
            ['name' => 'Walton',     'slug' => 'walton'],
            ['name' => 'Vision',     'slug' => 'vision'],
            ['name' => 'Marcel',     'slug' => 'marcel'],
            ['name' => 'Pran',       'slug' => 'pran'],
            ['name' => 'ACI',        'slug' => 'aci'],
            ['name' => 'Square',     'slug' => 'square'],
            ['name' => 'Marico',     'slug' => 'marico'],
            ['name' => 'Unilever',   'slug' => 'unilever'],
            ['name' => 'Nestle',     'slug' => 'nestle'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['slug' => $brand['slug']], array_merge($brand, ['is_active' => true]));
        }

        // ── Root Categories with Translations ─────────────────────────────────
        $categories = [
            [
                'en' => ['name' => 'Electronics',       'slug' => 'electronics'],
                'bn' => ['name' => 'ইলেকট্রনিক্স',       'slug' => 'electronics-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Mobile Phones',     'slug' => 'mobile-phones'],
                        'bn' => ['name' => 'মোবাইল ফোন',        'slug' => 'mobile-phones-bn'],
                    ],
                    [
                        'en' => ['name' => 'Laptops & Computers','slug' => 'laptops-computers'],
                        'bn' => ['name' => 'ল্যাপটপ ও কম্পিউটার', 'slug' => 'laptops-computers-bn'],
                    ],
                    [
                        'en' => ['name' => 'Televisions',       'slug' => 'televisions'],
                        'bn' => ['name' => 'টেলিভিশন',           'slug' => 'televisions-bn'],
                    ],
                    [
                        'en' => ['name' => 'Cameras',           'slug' => 'cameras'],
                        'bn' => ['name' => 'ক্যামেরা',            'slug' => 'cameras-bn'],
                    ],
                    [
                        'en' => ['name' => 'Audio & Headphones','slug' => 'audio-headphones'],
                        'bn' => ['name' => 'অডিও ও হেডফোন',      'slug' => 'audio-headphones-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Fashion',            'slug' => 'fashion'],
                'bn' => ['name' => 'ফ্যাশন',              'slug' => 'fashion-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Men\'s Clothing',   'slug' => 'mens-clothing'],
                        'bn' => ['name' => 'পুরুষের পোশাক',      'slug' => 'mens-clothing-bn'],
                    ],
                    [
                        'en' => ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing'],
                        'bn' => ['name' => 'মহিলার পোশাক',       'slug' => 'womens-clothing-bn'],
                    ],
                    [
                        'en' => ['name' => 'Footwear',          'slug' => 'footwear'],
                        'bn' => ['name' => 'জুতা',               'slug' => 'footwear-bn'],
                    ],
                    [
                        'en' => ['name' => 'Bags & Accessories','slug' => 'bags-accessories'],
                        'bn' => ['name' => 'ব্যাগ ও আনুষাঙ্গিক', 'slug' => 'bags-accessories-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Home & Living',      'slug' => 'home-living'],
                'bn' => ['name' => 'ঘর ও জীবনযাপন',     'slug' => 'home-living-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Furniture',         'slug' => 'furniture'],
                        'bn' => ['name' => 'আসবাবপত্র',          'slug' => 'furniture-bn'],
                    ],
                    [
                        'en' => ['name' => 'Kitchen & Dining',  'slug' => 'kitchen-dining'],
                        'bn' => ['name' => 'রান্নাঘর ও ডাইনিং', 'slug' => 'kitchen-dining-bn'],
                    ],
                    [
                        'en' => ['name' => 'Bedding & Bath',    'slug' => 'bedding-bath'],
                        'bn' => ['name' => 'বিছানা ও বাথরুম',   'slug' => 'bedding-bath-bn'],
                    ],
                    [
                        'en' => ['name' => 'Home Appliances',   'slug' => 'home-appliances'],
                        'bn' => ['name' => 'গৃহস্থালি যন্ত্রপাতি', 'slug' => 'home-appliances-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Grocery & Food',     'slug' => 'grocery-food'],
                'bn' => ['name' => 'মুদি ও খাবার',       'slug' => 'grocery-food-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Rice & Grains',     'slug' => 'rice-grains'],
                        'bn' => ['name' => 'চাল ও শস্য',         'slug' => 'rice-grains-bn'],
                    ],
                    [
                        'en' => ['name' => 'Oil & Ghee',        'slug' => 'oil-ghee'],
                        'bn' => ['name' => 'তেল ও ঘি',           'slug' => 'oil-ghee-bn'],
                    ],
                    [
                        'en' => ['name' => 'Beverages',         'slug' => 'beverages'],
                        'bn' => ['name' => 'পানীয়',              'slug' => 'beverages-bn'],
                    ],
                    [
                        'en' => ['name' => 'Snacks',            'slug' => 'snacks'],
                        'bn' => ['name' => 'স্ন্যাকস',            'slug' => 'snacks-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Health & Beauty',    'slug' => 'health-beauty'],
                'bn' => ['name' => 'স্বাস্থ্য ও সৌন্দর্য', 'slug' => 'health-beauty-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Skincare',          'slug' => 'skincare'],
                        'bn' => ['name' => 'ত্বকের যত্ন',        'slug' => 'skincare-bn'],
                    ],
                    [
                        'en' => ['name' => 'Haircare',          'slug' => 'haircare'],
                        'bn' => ['name' => 'চুলের যত্ন',         'slug' => 'haircare-bn'],
                    ],
                    [
                        'en' => ['name' => 'Vitamins & Supplements','slug' => 'vitamins-supplements'],
                        'bn' => ['name' => 'ভিটামিন ও সাপ্লিমেন্ট', 'slug' => 'vitamins-supplements-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Sports & Outdoors',  'slug' => 'sports-outdoors'],
                'bn' => ['name' => 'খেলাধুলা ও আউটডোর', 'slug' => 'sports-outdoors-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Exercise Equipment','slug' => 'exercise-equipment'],
                        'bn' => ['name' => 'ব্যায়াম সরঞ্জাম',    'slug' => 'exercise-equipment-bn'],
                    ],
                    [
                        'en' => ['name' => 'Sports Clothing',   'slug' => 'sports-clothing'],
                        'bn' => ['name' => 'স্পোর্টস পোশাক',    'slug' => 'sports-clothing-bn'],
                    ],
                    [
                        'en' => ['name' => 'Cricket',           'slug' => 'cricket'],
                        'bn' => ['name' => 'ক্রিকেট',            'slug' => 'cricket-bn'],
                    ],
                    [
                        'en' => ['name' => 'Football',          'slug' => 'football'],
                        'bn' => ['name' => 'ফুটবল',              'slug' => 'football-bn'],
                    ],
                ],
            ],
            [
                'en' => ['name' => 'Books & Stationery', 'slug' => 'books-stationery'],
                'bn' => ['name' => 'বই ও স্টেশনারি',     'slug' => 'books-stationery-bn'],
                'children' => [
                    [
                        'en' => ['name' => 'Bangla Books',      'slug' => 'bangla-books'],
                        'bn' => ['name' => 'বাংলা বই',           'slug' => 'bangla-books-bn'],
                    ],
                    [
                        'en' => ['name' => 'English Books',     'slug' => 'english-books'],
                        'bn' => ['name' => 'ইংরেজি বই',          'slug' => 'english-books-bn'],
                    ],
                    [
                        'en' => ['name' => 'Office Supplies',   'slug' => 'office-supplies'],
                        'bn' => ['name' => 'অফিস সামগ্রী',       'slug' => 'office-supplies-bn'],
                    ],
                ],
            ],
        ];

        $sort = 0;
        foreach ($categories as $catData) {
            $children = $catData['children'] ?? [];
            unset($catData['children']);

            $parent = Category::firstOrCreate(
                ['id' => Category::where('id', '>', 0)
                    ->whereHas('translations', fn($q) => $q->where('locale', 'en')->where('slug', $catData['en']['slug']))
                    ->value('id') ?? 0],
                ['parent_id' => null, 'sort_order' => $sort++, 'is_active' => true]
            );

            // Re-query cleanly
            $enTrans = CategoryTranslation::where('locale', 'en')->where('slug', $catData['en']['slug'])->first();
            if ($enTrans) {
                $parent = Category::find($enTrans->category_id);
            } else {
                $parent = Category::create(['parent_id' => null, 'sort_order' => $sort - 1, 'is_active' => true]);
                CategoryTranslation::create(array_merge($catData['en'], ['category_id' => $parent->id, 'locale' => 'en']));
                CategoryTranslation::create(array_merge($catData['bn'], ['category_id' => $parent->id, 'locale' => 'bn']));
            }

            $childSort = 0;
            foreach ($children as $childData) {
                $childEnTrans = CategoryTranslation::where('locale', 'en')->where('slug', $childData['en']['slug'])->first();
                if (!$childEnTrans) {
                    $child = Category::create(['parent_id' => $parent->id, 'sort_order' => $childSort, 'is_active' => true]);
                    CategoryTranslation::create(array_merge($childData['en'], ['category_id' => $child->id, 'locale' => 'en']));
                    CategoryTranslation::create(array_merge($childData['bn'], ['category_id' => $child->id, 'locale' => 'bn']));
                }
                $childSort++;
            }
        }

        $this->command->info('  Brands & categories seeded.');
    }
}
