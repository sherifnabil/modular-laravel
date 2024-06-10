<?php

namespace Modules\Order\Events\Listeners;

use Modules\Order\Events\OrderFullFilled;
use Modules\Product\Warehouse\ProductStockManger;

class DecreaseProductStock
{
    public function __construct(
        protected ProductStockManger $productStockManger
    ) {
   }

    public function handle(OrderFullFilled $event): void
    {
        foreach ($event->cartItems->items() as $cartItem) {
            $this->productStockManger->decrement($cartItem->product->id, $cartItem->quantity);
        }

    }
}
