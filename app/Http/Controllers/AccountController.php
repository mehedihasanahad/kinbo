<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $recentOrders = $user->orders()->with(['items'])->latest()->take(5)->get();
        $orderCount   = $user->orders()->count();
        $reviewCount  = $user->reviews()->count();
        $wishlistCount = $user->wishlist()->count();
        $addressCount = $user->addresses()->count();

        return view('account.index', compact(
            'user', 'recentOrders', 'orderCount', 'reviewCount', 'wishlistCount', 'addressCount'
        ));
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()
            ->with(['product.translations', 'product.primaryImage'])
            ->latest()
            ->paginate(10);

        return view('account.reviews', compact('reviews'));
    }
}
