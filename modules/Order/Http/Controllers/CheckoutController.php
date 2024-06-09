<?php

namespace Modules\Order\Http\Controllers;

use Modules\Payment\PayBuddy;
use Modules\Order\Models\Order;
use Illuminate\Http\JsonResponse;
use Modules\Product\CartItemCollection;
use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequest;
class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems
    ) {
    }

    public function __invoke(CheckoutRequest $request)
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        try {
            $order = $this->purchaseItems->handle(
                cartItemCollection: $cartItems,
                paymeentProvider: PayBuddy::make(),
                paymentToken: $request->input('payment_token'),
                userId: $request->user()->id
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment'
            ]);
        }

        return new JsonResponse([
            'order_url' => $order->url(),
        ], 201);
    }
}
