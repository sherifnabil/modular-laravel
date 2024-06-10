<?php

namespace Modules\Order\ServiceProviders;

use Modules\Order\Events\OrderFullFilled;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Events\Listeners\DecreaseProductStock;
use Modules\Order\Events\Listeners\SendOrderConfirmationEmail;

class EventServiceProvider extends BaseEventServiceProvider
{
   protected $listen = [
        OrderFullFilled::class => [
            SendOrderConfirmationEmail::class,
            DecreaseProductStock::class,
        ]
   ];
}
