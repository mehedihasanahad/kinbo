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
        View::composer('layouts.app', function ($view) {
            $cartCount = auth()->check()
                ? auth()->user()->cartItems()->sum('quantity')
                : 0;

            $announcementBarText = \App\Models\Setting::get('announcement_bar_text', '');

            $view->with(compact(
                'cartCount',
                'announcementBarText'
            ));
        });
    }
}
