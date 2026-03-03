<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create();

        // ── Home ──────────────────────────────────────────────────────────────
        $sitemap->add(
            Url::create(route('home'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // ── Category pages ────────────────────────────────────────────────────
        $categories = Category::active()
            ->with('translations')
            ->get();

        foreach ($categories as $category) {
            // Prefer English slug, fall back to any available translation
            $translation = $category->getTranslation('en')
                ?? $category->translations->first();

            if (! $translation?->slug) {
                continue;
            }

            $sitemap->add(
                Url::create(route('shop.category', ['category' => $translation->slug]))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($category->updated_at)
            );
        }

        // ── Product pages ─────────────────────────────────────────────────────
        $products = Product::active()
            ->with('translations')
            ->get();

        foreach ($products as $product) {
            $translation = $product->getTranslation('en')
                ?? $product->translations->first();

            if (! $translation?->slug) {
                continue;
            }

            $sitemap->add(
                Url::create(route('product.show', $translation->slug))
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($product->updated_at)
            );
        }

        return response(
            $sitemap->render(),
            200,
            ['Content-Type' => 'application/xml']
        );
    }
}
