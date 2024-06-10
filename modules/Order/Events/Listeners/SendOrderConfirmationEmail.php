<?php

namespace Modules\Order\Events\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Events\OrderFullFilled;
use Modules\Order\Mail\OrderReceived;

class SendOrderConfirmationEmail
{

    public function handle(OrderFullFilled $event): void
    {
        Mail::to($event->userEmail)->send(new OrderReceived($event->localizedTotal));
    }
}
