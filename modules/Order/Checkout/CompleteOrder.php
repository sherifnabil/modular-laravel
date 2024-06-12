<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Order;
use Modules\Order\Checkout\PaymentSucceeded;

class CompleteOrder implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        Order::find($event->order->id)->complete();
    }
}
