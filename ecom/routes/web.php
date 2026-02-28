<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ShopController::class, 'category'])->name('shop.category');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->where('locale', 'en|bn')
    ->name('lang.switch');
