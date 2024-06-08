<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\CheckoutController;

Route::middleware('auth')->group(function() {
    Route::post('order-checkout', CheckoutController::class)->name('order.checkout');
});
