<?php

namespace Modules\Order\Tests\Checkout;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Modules\Order\Checkout\CompleteOrder;
use Modules\Order\Checkout\DecreaseProductStock;
use Tests\TestCase;

class PaymentSucceeded extends TestCase
{
    use DatabaseTransactions;

    public function test_it_has_listeners(): void
    {
        Event::fake();

        Event::assertListening(PaymentSucceeded::class, DecreaseProductStock::class);
        Event::assertListening(PaymentSucceeded::class, CompleteOrder::class);
    }
}
