<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;

readonly class MarkOrderAsFailed implements ShouldQueue
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user,
    ) {
    }
}
