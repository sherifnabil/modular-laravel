<?php

namespace Modules\Order\Tests\Checkout;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Order\Checkout\OrderReceived;
use Tests\TestCase;

class OrderReceivedTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_renders_the_mailable()
    {
        $orderDto = new OrderDto(
            id: 1,
            totalInPiasters: 500,
            localizedTotal: 'EGP 500',
            url: route('order::order.show', 1),
            lines: [],
        );
        $orderReceived = new OrderReceived($orderDto);

        $this->assertIsString($orderReceived->render());
    }
}
