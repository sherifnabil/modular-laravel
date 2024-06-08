<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Paymennt\PayBuddy;
use Modules\Product\Models\Product;

class CheckoutController
{
    public function __invoke(CheckoutRequest $request)
    {
        $products = collect($request->input('products'))->map(function ($productDetails) {
            return [
                'product' => Product::find($productDetails['id']),
                'quantity' => $productDetails['quantity']
            ];
        });

        $orderTotalInPiasters = $products->sum(
            fn ($productDetails) =>
            $productDetails['quantity'] * $productDetails['product']['price']
        );

        $payBuddy = PayBuddy::make();

        try {
            $charge = $payBuddy->charge($request->input('payment_token'), $orderTotalInPiasters, 'pay shopping');
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment'
            ]);
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'total_in_piasters' => $orderTotalInPiasters,
            'payment_id' => $charge['id'],
            'status'    =>  'paid',
            'payment_gateway'   =>  'PayBuddy',
        ]);

        foreach ($products as $product) {
            $order->lines()->create([
                'product_id' => $product['product']['id'],
                'product_price_in_piasters' => $product['product']['price'],
                'quantity' => $product['quantity'],
            ]);
        }

        return new JsonResponse([], 201);
    }
}
