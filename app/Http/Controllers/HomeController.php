<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = Setting::group('general');

        $banners = Banner::active()->get();

        $categories = Category::active()
            ->root()
            ->with(['translations', 'children.translations'])
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $featuredProducts = Product::featured()
            ->inStock()
            ->with(['primaryImage', 'translations', 'brand', 'category.translations'])
            ->orderBy('sort_order')
            ->take(12)
            ->get();

        $newArrivals = Product::featured()
            ->inStock()
            ->with(['primaryImage', 'translations', 'brand'])
            ->orderBy('sort_order')
            ->get();

        $onSaleProducts = Product::active()
            ->inStock()
            ->whereNotNull('sale_price')
            ->with(['primaryImage', 'translations'])
            ->orderByRaw('((price - sale_price) / price) DESC')
            ->take(12)
            ->get();

        $testimonials = Review::approved()
            ->with(['user', 'product.translations'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(9)
            ->get();

        $promoImg    = Setting::get('promo_banner_image', '');
        $promoBanner = [
            'enabled'     => (bool) Setting::get('promo_banner_enabled', '1'),
            'image'       => $promoImg ? asset('storage/' . $promoImg) : null,
            'label'       => Setting::get('promo_banner_label', 'Up To'),
            'headline'    => Setting::get('promo_banner_headline', '20% OFF'),
            'subtext'     => Setting::get('promo_banner_subtext', 'On New Collection'),
            'button_text' => Setting::get('promo_banner_button_text', 'Shop Now'),
            'button_url'  => Setting::get('promo_banner_button_url', ''),
        ];

        return view('home', compact(
            'settings',
            'banners',
            'categories',
            'featuredProducts',
            'newArrivals',
            'onSaleProducts',
            'testimonials',
            'promoBanner',
        ));
    }
}
