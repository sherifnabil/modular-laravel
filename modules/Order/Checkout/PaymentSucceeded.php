<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Checkout\Contracts\OrderDto;

class PaymentSucceeded implements ShouldQueue
{
    public function __construct(
        public OrderDto $order
    ) {
    }
}
