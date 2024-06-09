<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManger;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManger $productStockManger,
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager
    ) {
    }

    public function handle(CartItemCollection $cartItemCollection, PayBuddy $paymeentProvider, string $paymentToken, int $userId): Order
    {

        return $this->databaseManager->transaction(function () use ($cartItemCollection, $paymeentProvider, $paymentToken, $userId){

            $orderTotalInPiasters = $cartItemCollection->totalInPiasters();

            $order = Order::create([
                'user_id' => $userId,
                'total_in_piasters' => $orderTotalInPiasters,
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

            $payment = $this->createPaymentForOrder->handle(
                $order->id,
                $userId,
                $orderTotalInPiasters,
                $paymeentProvider,
                $paymentToken,
            );

            return $order;
        });
    }
}
