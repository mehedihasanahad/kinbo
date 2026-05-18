<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
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
            ->take(4)
            ->get();

        $onSaleProducts = Product::active()
            ->inStock()
            ->whereNotNull('sale_price')
            ->with(['primaryImage', 'translations', 'brand'])
            ->orderByRaw('((price - sale_price) / price) DESC')
            ->take(12)
            ->get();

        $brands = Brand::active()
            ->whereNotNull('logo')
            ->orderBy('name')
            ->take(12)
            ->get();

        $testimonials = Review::approved()
            ->with(['user', 'product.translations'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(9)
            ->get();

        return view('home', compact(
            'settings',
            'banners',
            'categories',
            'featuredProducts',
            'newArrivals',
            'onSaleProducts',
            'brands',
            'testimonials',
        ));
    }
}
