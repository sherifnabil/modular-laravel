<?php

namespace Modules\Order\Events;

use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\UserDto;
use Modules\Product\CartItemCollection;

readonly class OrderFullFilled
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    ) {
    }
}
