<?php

namespace Modules\Order\Actions;

use Illuminate\Validation\ValidationException;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManger;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManger $productStockManger
    ) {
    }

    public function handle(CartItemCollection $cartItemCollection, PayBuddy $paymeentProvider, string $paymentToken, int $userId): Order
    {

        $orderTotalInPiasters = $cartItemCollection->totalInPiasters();

        try {
            $charge = $paymeentProvider->charge($paymentToken, $orderTotalInPiasters, 'pay shopping');
        } catch (\Throwable $th) {
            throw PaymentFailedException::dueToInvalidToken();
        }

        $order = Order::create([
            'user_id' => $userId,
            'total_in_piasters' => $orderTotalInPiasters,
            'payment_id' => $charge['id'],
            'status'    =>  'completed',
            'payment_gateway'   =>  'PayBuddy',
        ]);

        foreach ($cartItemCollection->items() as $cartItem) {
            $this->productStockManger->decrement($cartItem->product->id, $cartItem->quantity);

            $order->lines()->create([
                'product_id' => $cartItem->product->id,
                'product_price_in_piasters' => $cartItem->product->priceInPiasters,
                'quantity' => $cartItem->quantity,
            ]);
        }

        $order->payments()->create([
            'payment_id' => $charge['id'],
            'total_in_piasters' => $orderTotalInPiasters,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'user_id' => $userId,
        ]);

        return $order;
    }
}
