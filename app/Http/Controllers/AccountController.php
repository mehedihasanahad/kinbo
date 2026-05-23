<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

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

    public function password(): View
    {
        return view('account.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // OAuth-only users never set a password — skip current_password check
        if (! $user->provider) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $request->validate($rules);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('password_updated', true);
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
