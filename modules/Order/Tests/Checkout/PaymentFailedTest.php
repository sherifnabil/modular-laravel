<?php

namespace Modules\Order\Tests\Checkout;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Modules\Order\Checkout\CompleteOrder;
use Modules\Order\Checkout\DecreaseProductStock;
use Modules\Order\Checkout\MarkOrderAsFailed;
use Modules\Order\Checkout\NotifyUserOfPaymentFailure;
use Modules\Order\Checkout\PaymentFailed;
use Tests\TestCase;

class PaymentFailedTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_has_listeners(): void
    {
        Event::fake();

        Event::assertListening(PaymentFailed::class, NotifyUserOfPaymentFailure::class);
        Event::assertListening(PaymentFailed::class, MarkOrderAsFailed::class);
    }
}
