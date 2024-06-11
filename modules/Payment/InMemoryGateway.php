<?php

namespace Modules\Payment;

use Illuminate\Support\Str;

class InMemoryGateway implements PaymentGateway
{
    public function charge(PaymentDetails $details): SuccessfullPayment
    {
        return new SuccessfullPayment(
            (string) Str::uuid(),
            $details->amountInPiasters,
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::InMemory;
    }
}
