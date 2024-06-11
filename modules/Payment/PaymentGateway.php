<?php

namespace Modules\Payment;

interface PaymentGateway
{
    public function charge(PaymentDetails $paymentDetails): SuccessfullPayment;

    public function id(): PaymentProvider;
}
