<?php

namespace Modules\Order\Checkout;

use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Order\Checkout\Contracts\UserDto;

class PaymentFailed
{
    public function __construct(
        public UserDto $user,
        public OrderDto $order,
        public string $reason,
    ) {
    }
}
