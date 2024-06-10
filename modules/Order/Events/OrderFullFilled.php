<?php

namespace Modules\Order\Events;

use Modules\Product\CartItemCollection;

readonly class OrderFullFilled
{

    public function __construct(
        public int $orderId,
        public int $totalInPiasters,
        public int $userId,
        public string $userEmail,
        public string $localizedTotal,
        public CartItemCollection $cartItems,
    ) {

    }
}
