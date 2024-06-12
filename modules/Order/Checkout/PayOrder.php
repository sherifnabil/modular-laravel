<?php

namespace Modules\Order\Checkout;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\Exceptions\PaymentFailedException;

class PayOrder implements ShouldQueue
{
    public function __construct(
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected Dispatcher $events
    ) {
    }

    public function handle(OrderStarted $event): void
    {
        try {
            $this->createPaymentForOrder->handle(
                $event->order->id,
                $event->user->id,
                $event->order->totalInPiasters,
                $event->pendingPayment->provider,
                $event->pendingPayment->paymentToken,
            );
        } catch (PaymentFailedException $e) {
            $this->events->dispatch(new PaymentFailed(
                $event->user,
                $event->order,
                $e->getMessage(),
            ));

            throw $e;
        }

        $this->events->dispatch(new PaymentSucceeded($event->order, $event->user));
    }
}
