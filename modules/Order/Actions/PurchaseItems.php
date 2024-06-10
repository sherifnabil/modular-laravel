<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\DTOs\UserDto;
use Modules\Order\Events\OrderFullFilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Product\CartItemCollection;

class PurchaseItems
{
    public function __construct(
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {
    }

    public function handle(CartItemCollection $cartItemCollection, PendingPayment $pendingPayment, UserDto $user): OrderDto
    {
        $order = $this->databaseManager->transaction(function () use ($cartItemCollection, $pendingPayment, $user) {
            $order = Order::startForUser($user->id);
            $order->addLinesFromCartItems($cartItemCollection);
            $order->fullfill();

            $payment = $this->createPaymentForOrder->handle(
                $order->id,
                $user->id,
                $cartItemCollection->totalInPiasters(),
                $pendingPayment->provider,
                $pendingPayment->paymentToken,
            );

            return OrderDto::fromEloquentModel($order);
        });

        $this->events->dispatch(new OrderFullFilled($order, $user));

        return $order;
    }
}
