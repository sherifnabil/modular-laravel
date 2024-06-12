<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Order\Checkout\OrderReceived;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderStarted $event): void
    {
        Mail::to($event->user->email)->send(new OrderReceived(new OrderDto(
            $event->order->id,
            $event->order->totalInPiasters,
            $event->order->localizedTotal,
            $event->order->url,
            $event->order->lines,
        )));
    }
}
