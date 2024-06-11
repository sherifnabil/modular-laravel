<?php

namespace Modules\Order\Checkout;

use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;

readonly class OrderFullFilled
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    ) {
    }
}
