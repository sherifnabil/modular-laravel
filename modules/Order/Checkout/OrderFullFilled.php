<?php

namespace Modules\Order\Checkout;

use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Order\Checkout\Contracts\PendingPayment;

readonly class OrderFullFilled
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
        public PendingPayment $pendingPayment,
    ) {
    }
}
