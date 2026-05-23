<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function category(Request $request): View
    {
        $locale   = app()->getLocale();
        $category = null;
        $slug     = $request->query('category') ?: null;

        // ── Search query ───────────────────────────────────────────────────────
        $q        = trim((string) $request->query('q', ''));
        $isSearch = $q !== '';

        // ── Resolve category from optional ?category= query param ─────────────
        if ($slug !== null) {
            $translation = CategoryTranslation::where('slug', $slug)
                ->where('locale', $locale)
                ->first()
                ?? CategoryTranslation::where('slug', $slug)
                    ->where('locale', 'en')
                    ->first();

            if ($translation) {
                $category = Category::active()
                    ->with(['translations', 'children.translations', 'parent.translations'])
                    ->find($translation->category_id);
            }

            if (! $category) {
                $slug = null;
            }
        }

        // ── Filter inputs ──────────────────────────────────────────────────────
        $allowedSorts = ['newest', 'price_asc', 'price_desc', 'discount'];
        $sort = $request->query('sort', 'newest');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'newest';
        }

        $priceMin = is_numeric($request->query('price_min')) ? (float) $request->query('price_min') : null;
        $priceMax = is_numeric($request->query('price_max')) ? (float) $request->query('price_max') : null;
        $view     = in_array($request->query('view'), ['grid', 'list']) ? $request->query('view') : 'grid';

        // ── Product query ──────────────────────────────────────────────────────
        $query = Product::active()->with(['primaryImage', 'translations']);

        if ($isSearch) {
            $query->whereHas('translations', function ($tq) use ($q) {
                $tq->where('name', 'LIKE', '%' . $q . '%')
                   ->orWhere('short_description', 'LIKE', '%' . $q . '%');
            });
        }

        if ($category !== null) {
            $categoryIds = $category->children->pluck('id')->push($category->id)->toArray();
            $query->whereIn('category_id', $categoryIds);
        }

        if ($priceMin !== null) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$priceMin]);
        }

        if ($priceMax !== null) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$priceMax]);
        }

        match ($sort) {
            'price_asc'  => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'discount'   => $query->orderByRaw('
                CASE WHEN sale_price IS NOT NULL AND sale_price < price
                THEN ((price - sale_price) / price)
                ELSE 0 END DESC
            '),
            default => $query->latest(),
        };

        $products = $query->paginate(24)->withQueryString();

        // ── Sidebar data ───────────────────────────────────────────────────────
        $sidebarCategories = Category::active()
            ->root()
            ->with(['translations', 'children.translations'])
            ->orderBy('sort_order')
            ->get();

        return view('shop.category', compact(
            'category',
            'slug',
            'products',
            'sidebarCategories',
            'sort',
            'priceMin',
            'priceMax',
            'view',
            'locale',
            'q',
            'isSearch',
        ));
    }
}
