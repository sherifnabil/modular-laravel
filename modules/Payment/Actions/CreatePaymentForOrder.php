<?php

namespace Modules\Payment\Actions;

use RuntimeException;
use Modules\Payment\Payment;
use Modules\Payment\PaymentDetails;
use Modules\Payment\PaymentGateway;
use Modules\Payment\Exceptions\PaymentFailedException;

class CreatePaymentForOrder
{
    /**
     * @throws PaymentFailedException
     */
    public function handle(
        int $orderId,
        int $userId,
        int $totalInPiasters,
        PaymentGateway $paymentGateway,
        string $paymentToken
    ): Payment
    {
        $charge = $paymentGateway->charge(
            new PaymentDetails(
                $paymentToken,
                $totalInPiasters,
                'some Description',
            )
        );

        return Payment::create([
            'order_id' => $orderId,
            'payment_id' => $charge->id,
            'total_in_piasters' => $totalInPiasters,
            'status' => 'paid',
            'payment_gateway' => $charge->paymentProvider,
            'user_id' => $userId,
        ]);

    }
}
