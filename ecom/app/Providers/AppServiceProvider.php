<?php

namespace App\Providers;

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
        // Share google login flag with all views (needed in child views like auth/login)
        View::share('googleLoginEnabled',
            (bool) \App\Models\Setting::get('google_login_enabled', '0')
            && \App\Models\Setting::get('google_client_id', '') !== ''
            && \App\Models\Setting::get('google_client_secret', '') !== ''
        );

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
