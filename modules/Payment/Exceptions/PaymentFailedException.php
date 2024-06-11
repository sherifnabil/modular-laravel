<?php

namespace Modules\Payment\Exceptions;

use RuntimeException;

class PaymentFailedException extends RuntimeException
{
    public static function dueToInvalidToken(): self
    {
        return new static('Payment failed due to invalid token');
    }

}
