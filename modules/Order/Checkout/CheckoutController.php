<?php

namespace Modules\Order\Checkout;

use Illuminate\Http\JsonResponse;
use Modules\Payment\PaymentGateway;
use Modules\Product\CartItemCollection;
use Modules\Order\Checkout\PurchaseItems;
use Modules\Order\Checkout\CheckoutRequest;
use Modules\Order\Checkout\Contracts\UserDto;
use Illuminate\Validation\ValidationException;
use Modules\Order\Checkout\Contracts\PendingPayment;
use Modules\Payment\Exceptions\PaymentFailedException;

class CheckoutController
{
    public function __construct(
        protected PurchaseItems $purchaseItems,
        protected PaymentGateway $paymentGateway
    ) {
    }

    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        $cartItems = CartItemCollection::fromCheckoutData($request->input('products'));

        $pendingPayment = new PendingPayment($this->paymentGateway, $request->input('payment_token'));
        $userDto = UserDto::fromEloquentModel($request->user());

        try {
            $order = $this->purchaseItems->handle(
                cartItemCollection: $cartItems,
                pendingPayment: $pendingPayment,
                user: $userDto,
            );
        } catch (PaymentFailedException) {
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment'
            ]);
        }

        return new JsonResponse([
            'order_url' => $order->url,
        ], 201);
    }
}
