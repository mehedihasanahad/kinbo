<?php

use App\Http\Controllers\CourierWebhookController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SSLCommerz Payment Callback Routes
|--------------------------------------------------------------------------
|
| These routes are called by SSLCommerz's servers (not the user's browser),
| so they carry NO browser session cookie. They must run without session
| middleware to prevent Laravel from writing a new Set-Cookie header on the
| redirect response, which would overwrite the real user's session and log
| them out.
|
| Middleware used: SubstituteBindings only (no session, no CSRF, no cookies).
|
*/

Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/fail',    [PaymentController::class, 'fail'])->name('payment.fail');
Route::post('/payment/cancel',  [PaymentController::class, 'cancel'])->name('payment.cancel');

// Courier webhooks — no session/CSRF, bearer-token validated in controller
Route::post('/webhooks/courier/steadfast', [CourierWebhookController::class, 'steadfast'])->name('webhooks.courier.steadfast');
