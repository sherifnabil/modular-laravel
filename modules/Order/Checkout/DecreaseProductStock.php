<?php

namespace Modules\Order\Checkout;

use Modules\Order\Checkout\OrderFullFilled;
use Modules\Product\Warehouse\ProductStockManger;

class DecreaseProductStock
{
    public function __construct(
        protected ProductStockManger $productStockManger
    ) {
   }

    public function handle(OrderFullFilled $event): void
    {
        foreach ($event->order->lines as $cartItem) {
            $this->productStockManger->decrement($cartItem->productId, $cartItem->quantity);
        }
    }
}
