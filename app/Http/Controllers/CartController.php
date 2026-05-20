<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()
            ->with(['product.translations', 'product.primaryImage', 'variant.options'])
            ->get();

        $subtotal = $cartItems->sum('line_total');

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'integer|min:1|max:999',
            'custom_size' => 'nullable|string|max:255',
        ]);

        $variantId = $request->variant_id ?: null;
        $qty = (int) $request->get('quantity', 1);

        if ($variantId) {
            $variant = ProductVariant::findOrFail($variantId);
            $stock = $variant->stock;
        } else {
            $product = Product::findOrFail($request->product_id);

            if ($product->variants()->where('is_active', true)->exists()) {
                return back()->with('cart_error', __('front.select_variant_first'));
            }

            $stock = $product->stock;
        }

        if ($stock <= 0) {
            return back()->with('cart_error', __('front.insufficient_stock'));
        }

        $existing = auth()->user()->cartItems()
            ->where('product_id', $request->product_id)
            ->where('variant_id', $variantId)
            ->first();

        $currentInCart = $existing ? $existing->quantity : 0;
        $newTotal = $currentInCart + $qty;

        if ($newTotal > $stock) {
            $canAdd = $stock - $currentInCart;
            if ($canAdd <= 0) {
                return back()->with('cart_error', __('front.stock_limit_reached'));
            }
            // Cap to maximum available
            $qty = $canAdd;
        }

        $customSize = $request->input('custom_size') ?: null;

        if ($existing) {
            $existing->increment('quantity', $qty);
            if ($customSize !== null) {
                $existing->update(['custom_size' => $customSize]);
            }
        } else {
            auth()->user()->cartItems()->create([
                'product_id'  => $request->product_id,
                'variant_id'  => $variantId,
                'quantity'    => $qty,
                'custom_size' => $customSize,
            ]);
        }

        return back()->with('cart_success', __('front.added_to_cart'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== auth()->id(), 403);

        $request->validate(['quantity' => 'required|integer|min:1|max:999']);

        // Cap quantity to available stock
        $stock = $cartItem->variant
            ? $cartItem->variant->stock
            : $cartItem->product->stock;

        $qty = min((int) $request->quantity, $stock);

        $cartItem->update(['quantity' => $qty]);

        return back();
    }

    public function destroy(CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== auth()->id(), 403);

        $cartItem->delete();

        return back()->with('cart_success', __('front.item_removed'));
    }
}
