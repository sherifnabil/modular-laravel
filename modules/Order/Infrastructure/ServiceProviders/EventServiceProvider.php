<?php

namespace Modules\Order\Infrastructure\ServiceProviders;

use Modules\Order\Checkout\OrderFullFilled;
use Modules\Order\Checkout\DecreaseProductStock;
use Modules\Order\Checkout\SendOrderConfirmationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Checkout\CompleteOrder;
use Modules\Order\Checkout\MarkOrderAsFailed;
use Modules\Order\Checkout\NotifyUserOfPaymentFailure;
use Modules\Order\Checkout\OrderStarted;
use Modules\Order\Checkout\PaymentFailed;
use Modules\Order\Checkout\PaymentSucceeded;
use Modules\Order\Checkout\PayOrder;

class EventServiceProvider extends BaseEventServiceProvider
{
   protected $listen = [
        OrderFullFilled::class => [
            SendOrderConfirmationEmail::class,
            DecreaseProductStock::class,
        ],

        OrderStarted::class => [
            SendOrderConfirmationEmail::class,
        ],

        PaymentSucceeded::class => [
            CompleteOrder::class,
            DecreaseProductStock::class,
        ],

        PaymentFailed::class => [
            NotifyUserOfPaymentFailure::class,
            MarkOrderAsFailed::class,
        ],
   ];
}
