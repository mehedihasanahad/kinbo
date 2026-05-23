<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use App\Observers\BannerObserver;
use App\Observers\BrandObserver;
use App\Observers\CategoryObserver;
use App\Observers\ProductImageObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Super admin bypasses every Gate check
        Gate::before(function (\App\Models\User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        // Image optimization observers
        ProductImage::observe(ProductImageObserver::class);
        Banner::observe(BannerObserver::class);
        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);

        // Share google login flag with all views (needed in child views like auth/login)
        if (Schema::hasTable('settings')) {
            View::share('googleLoginEnabled',
                (bool) \App\Models\Setting::get('google_login_enabled', '0')
                && \App\Models\Setting::get('google_client_id', '') !== ''
                && \App\Models\Setting::get('google_client_secret', '') !== ''
            );
        } else {
            View::share('googleLoginEnabled', false);
        }

        View::composer('layouts.app', function ($view) {
            $cartCount = auth()->check()
                ? auth()->user()->cartItems()->sum('quantity')
                : \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity');

            $wishlistCount = auth()->check()
                ? auth()->user()->wishlist()->count()
                : 0;

            $announcementBarText = \App\Models\Setting::get('announcement_bar_text', '');

            $navCategories = \App\Models\Category::active()
                ->root()
                ->inNav()
                ->with('translations')
                ->orderBy('sort_order')
                ->take(8)
                ->get();

            $rawWhatsapp = preg_replace('/\D/', '', \App\Models\Setting::get('whatsapp_number', ''));
            $whatsappNumber = $rawWhatsapp !== ''
                ? (str_starts_with($rawWhatsapp, '0') ? '880' . substr($rawWhatsapp, 1) : $rawWhatsapp)
                : '';

            $sitePhone   = \App\Models\Setting::get('contact_phone', '');
            $siteEmail   = \App\Models\Setting::get('contact_email', '');
            $siteAddress = \App\Models\Setting::get('contact_address', '');

            $view->with(compact(
                'cartCount',
                'wishlistCount',
                'announcementBarText',
                'navCategories',
                'whatsappNumber',
                'sitePhone',
                'siteEmail',
                'siteAddress'
            ));
        });
    }
}
