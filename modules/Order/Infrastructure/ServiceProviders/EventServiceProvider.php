<?php

namespace Modules\Order\Infrastructure\ServiceProviders;

use Modules\Order\Checkout\OrderFullFilled;
use Modules\Order\Checkout\DecreaseProductStock;
use Modules\Order\Checkout\SendOrderConfirmationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
   protected $listen = [
        OrderFullFilled::class => [
            SendOrderConfirmationEmail::class,
            DecreaseProductStock::class,
        ]
   ];
}
