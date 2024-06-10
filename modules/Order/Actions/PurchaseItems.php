<?php

namespace Modules\Order\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Modules\Order\Events\OrderFullFilled;
use Modules\Order\Mail\OrderReceived;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\PayBuddy;
use Modules\Product\CartItemCollection;

class PurchaseItems
{
    public function __construct(
        protected CreatePaymentForOrder $createPaymentForOrder,
        protected DatabaseManager $databaseManager,
        protected Dispatcher $events
    ) {
    }

    public function handle(CartItemCollection $cartItemCollection, PayBuddy $paymeentProvider, string $paymentToken, int $userId, string $userEmail): Order
    {
        return $this->databaseManager->transaction(function () use ($cartItemCollection, $paymeentProvider, $paymentToken, $userId, $userEmail) {
            $order = Order::startForUser($userId);
            $order->addLinesFromCartItems($cartItemCollection);
            $order->fullfill();

            $payment = $this->createPaymentForOrder->handle(
                $order->id,
                $userId,
                $cartItemCollection->totalInPiasters(),
                $paymeentProvider,
                $paymentToken,
            );

            $this->events->dispatch(new OrderFullFilled(
                $order->id,
                $order->total_in_piasters,
                $userId,
                $userEmail,
                $payment->total_in_piasters,
                $cartItemCollection,
            ));

            return $order;
        });
    }
}
