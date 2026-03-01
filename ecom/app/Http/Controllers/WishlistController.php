<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()
            ->with(['product.translations', 'product.primaryImage', 'product.variants'])
            ->get()
            ->filter(fn($item) => $item->product !== null);

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $exists = auth()->user()->wishlist()
            ->where('product_id', $request->product_id)
            ->exists();

        if (! $exists) {
            auth()->user()->wishlist()->create([
                'product_id' => $request->product_id,
            ]);
        }

        $count = auth()->user()->wishlist()->count();

        return response()->json([
            'in_wishlist' => true,
            'wishlist_count' => $count,
            'message' => __('front.added_to_wishlist'),
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        $item = auth()->user()->wishlist()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->delete();
        }

        $count = auth()->user()->wishlist()->count();

        return response()->json([
            'in_wishlist' => false,
            'wishlist_count' => $count,
            'message' => __('front.removed_from_wishlist'),
        ]);
    }

    public function moveToCart(Request $request, Product $product)
    {
        // Products with variants require the user to choose options first
        if ($product->variants()->exists()) {
            $locale = app()->getLocale();
            $translation = $product->getTranslation($locale) ?? $product->getTranslation('en');
            $slug = $translation?->slug ?? $product->sku;

            return redirect()->route('product.show', $slug)
                ->with('info', __('front.select_options_to_add'));
        }

        // Remove from wishlist
        auth()->user()->wishlist()
            ->where('product_id', $product->id)
            ->delete();

        // Add to cart (one unit, no variant)
        $stock = $product->stock;

        if ($stock > 0) {
            $existing = auth()->user()->cartItems()
                ->where('product_id', $product->id)
                ->whereNull('variant_id')
                ->first();

            if ($existing) {
                $existing->increment('quantity');
            } else {
                auth()->user()->cartItems()->create([
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'quantity'   => 1,
                ]);
            }
        }

        return back()->with('cart_success', __('front.moved_to_cart'));
    }
}
