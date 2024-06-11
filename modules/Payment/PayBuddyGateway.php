<?php

namespace Modules\Payment;

use RuntimeException;
use Modules\Payment\Exceptions\PaymentFailedException;

class PayBuddyGateway implements PaymentGateway
{
    public function __construct(
        protected PayBuddySdk $payBuddySdk
    ) {
    }

    /**
     *
     * @param PaymentDetails $details
     * @return SuccessfullPayment
     * @throws PaymentFailedException
     */
    public function charge(PaymentDetails $details): SuccessfullPayment
    {
        try {
            $response = $this->payBuddySdk->charge(
                $details->token,
                $details->amountInPiasters,
                $details->statementDescription
            );

            return new SuccessfullPayment($response['id'], $response['amount_in_piasters'], $this->id());
        } catch (RuntimeException $e) {
            throw new PaymentFailedException($e->getMessage());
        }
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PayBuddy;
    }
}
