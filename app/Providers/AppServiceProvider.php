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
                : 0;

            $wishlistCount = auth()->check()
                ? auth()->user()->wishlist()->count()
                : 0;

            $announcementBarText = \App\Models\Setting::get('announcement_bar_text', '');

            $view->with(compact(
                'cartCount',
                'wishlistCount',
                'announcementBarText'
            ));
        });
    }
}
