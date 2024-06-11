<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\CheckoutController;
use Modules\Order\Models\Order;

Route::get('order/{id}', fn(Order $order) =>view('order::checkout.index'))->name('order.show');
Route::middleware('auth')->group(function() {
    Route::post('order-checkout', CheckoutController::class)->name('order.checkout');

    // Route::get('order/{id}', fn(Order $order) => $order)->name('order.show');
});
