<?php

namespace Modules\Order\Tests\Checkout;

use Tests\TestCase;
use Modules\Order\Checkout\PayOrder;
use Illuminate\Support\Facades\Event;
use Modules\Order\Checkout\OrderStarted;
use Modules\Order\Checkout\SendOrderConfirmationEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderStartedTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_has_listeners(): void
    {
        Event::fake();

        Event::assertListening(OrderStarted::class, SendOrderConfirmationEmail::class);
        Event::assertListening(OrderStarted::class, PayOrder::class);
    }
}
