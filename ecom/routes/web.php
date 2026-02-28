<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->where('locale', 'en|bn')
    ->name('lang.switch');
