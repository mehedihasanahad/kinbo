<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $locale = app()->getLocale();

        // Resolve slug with locale fallback
        $translation = ProductTranslation::where('slug', $slug)
            ->where('locale', $locale)
            ->first()
            ?? ProductTranslation::where('slug', $slug)
                ->where('locale', 'en')
                ->first();

        if (! $translation) {
            abort(404);
        }

        $product = Product::active()
            ->with([
                'translations',
                'images',
                'primaryImage',
                'brand',
                'category.translations',
                'variants.options',
                'variants.images',
            ])
            ->findOrFail($translation->product_id);

        // Load approved reviews with user, latest first, limit 10
        $reviews = $product->reviews()
            ->approved()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $reviewCount = $product->reviews()->approved()->count();

        $avgRating = $reviewCount > 0
            ? round($product->reviews()->approved()->avg('rating'), 1)
            : 0;

        // Rating distribution 5 → 1
        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $product->reviews()->approved()->where('rating', $i)->count();
        }

        // Related products: same category, exclude self
        $relatedProducts = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage', 'translations', 'brand'])
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $currentTranslation = $product->getTranslation($locale);

        return view('product.show', compact(
            'product',
            'currentTranslation',
            'locale',
            'reviews',
            'reviewCount',
            'avgRating',
            'ratingCounts',
            'relatedProducts',
        ));
    }
}
