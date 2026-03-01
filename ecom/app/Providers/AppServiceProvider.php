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
            $view->with('cartCount', $cartCount);
        });
    }
}
