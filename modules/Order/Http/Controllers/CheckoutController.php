<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Paymennt\PayBuddy;
use Modules\Product\CartItem;
use Modules\Product\CartItemCollection;
use Modules\Product\Models\Product;
use Modules\Product\Warehouse\ProductStockManger;

class CheckoutController
{
    public function __construct(
        protected ProductStockManger $productStockManger
    ) {
    }

    public function __invoke(CheckoutRequest $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));
        $orderTotalInPiasters = $cartItems->totalInPiasters();

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

        foreach ($cartItems->items() as $cartItem) {
            $this->productStockManger->decrement($cartItem->product->id, $cartItem->quantity);

            $order->lines()->create([
                'product_id' => $cartItem->product->id,
                'product_price_in_piasters' => $cartItem->product->priceInPiasters,
                'quantity' => $cartItem->quantity,
            ]);
        }

        return new JsonResponse([], 201);
    }
}
