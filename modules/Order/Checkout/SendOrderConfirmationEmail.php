<?php

namespace Modules\Order\Checkout;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Checkout\OrderReceived;
use Modules\Order\Checkout\OrderFullFilled;

class SendOrderConfirmationEmail
{
    public function handle(OrderFullFilled $event): void
    {
        Mail::to($event->user->email)->send(new OrderReceived($event->order->localizedTotal));
    }
}
