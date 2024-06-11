<?php

namespace Modules\Payment;

readonly class SuccessfullPayment
{
    public function __construct(
        public string $id,
        public int $amountInPiasters,
        public PaymentProvider $paymentProvider,
    ) {
    }

}
