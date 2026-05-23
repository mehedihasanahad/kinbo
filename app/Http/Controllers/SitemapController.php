<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
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

        // ── Static pages ──────────────────────────────────────────────────────
        foreach (['page.about', 'page.contact', 'page.faq', 'page.privacy', 'page.terms'] as $name) {
            if (\Route::has($name)) {
                $sitemap->add(
                    Url::create(route($name))
                        ->setPriority(0.5)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
            }
        }

        // ── Blog ──────────────────────────────────────────────────────────────
        $locale = 'en';
        BlogPost::published()
            ->forLocale($locale)
            ->orderByDesc('published_at')
            ->each(function (BlogPost $post) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('page.blog.post', $post->slug))
                        ->setPriority(0.6)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($post->updated_at)
                );
            });

        // ── Category pages ────────────────────────────────────────────────────
        Category::active()
            ->with('translations')
            ->each(function (Category $category) use ($sitemap) {
                $translation = $category->getTranslation('en') ?? $category->translations->first();

                if (! $translation?->slug) {
                    return;
                }

                $sitemap->add(
                    Url::create(route('shop.category', ['category' => $translation->slug]))
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($category->updated_at)
                );
            });

        // ── Product pages ─────────────────────────────────────────────────────
        Product::active()
            ->with('translations')
            ->each(function (Product $product) use ($sitemap) {
                $translation = $product->getTranslation('en') ?? $product->translations->first();

                if (! $translation?->slug) {
                    return;
                }

                $sitemap->add(
                    Url::create(route('product.show', $translation->slug))
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($product->updated_at)
                );
            });

        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $content = Setting::get('robots_txt', implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Sitemap: ' . route('sitemap'),
        ]));

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
