<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Checkout\OrderFullFilled;
use Modules\Product\Warehouse\ProductStockManger;

class DecreaseProductStock implements ShouldQueue
{
    public function __construct(
        protected ProductStockManger $productStockManger
    ) {
   }

    public function handle(PaymentSucceeded $event): void
    {
        foreach ($event->order->lines as $cartItem) {
            $this->productStockManger->decrement($cartItem->productId, $cartItem->quantity);
        }
    }
}
