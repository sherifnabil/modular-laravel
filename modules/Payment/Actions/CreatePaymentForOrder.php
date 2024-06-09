<?php

namespace Modules\Payment\Actions;

use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Payment\Payment;

class CreatePaymentForOrder
{
    /**
     * @throws PaymentFailedException
     */
    public function handle(
        int $orderId,
        int $userId,
        int $totalInPiasters,
        PayBuddy $payBuddy,
        string $paymentToken
    ): Payment
    {
        try {
            $charge = $payBuddy->charge($paymentToken, $totalInPiasters, 'pay shopping');
        } catch (\Throwable $th) {
            throw PaymentFailedException::dueToInvalidToken();
        }

        return Payment::create([
            'order_id' => $orderId,
            'payment_id' => $charge['id'],
            'total_in_piasters' => $totalInPiasters,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'user_id' => $userId,
        ]);

    }
}
