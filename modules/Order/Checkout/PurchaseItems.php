<?php

namespace Modules\Order\Checkout;

use Modules\Order\Order;
use Illuminate\Events\Dispatcher;
use Modules\Product\CartItemCollection;
use Illuminate\Database\DatabaseManager;
use Modules\Order\Checkout\OrderFullFilled;
use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Order\Checkout\Contracts\PendingPayment;
use Modules\Product\Warehouse\ProductStockManger;

class PurchaseItems
{
    public function __construct(
        protected ProductStockManger $productStockManger,
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
            $order->start();

            $payment = $this->createPaymentForOrder->handle(
                $order->id,
                $user->id,
                $cartItemCollection->totalInPiasters(),
                $pendingPayment->provider,
                $pendingPayment->paymentToken,
            );

            return OrderDto::fromEloquentModel($order);
        });

        $this->events->dispatch(new OrderStarted($order, $user, $pendingPayment));

        return $order;
    }
}
