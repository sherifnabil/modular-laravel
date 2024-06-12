<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;

class NotifyUserOfPaymentFailure implements ShouldQueue
{
    public function handle(PaymentFailed $event)
    {
        Mail::to($event->user->email)->send(new PaymentForOrderFailed($event->order, $event->reason));
    }
}
