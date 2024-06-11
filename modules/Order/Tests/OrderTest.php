<?php

namespace Modules\Order\Tests;

use Tests\TestCase;
use Modules\Order\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_creates_an_order()
    {
        $order = Order::factory()->create();
        $this->assertTrue(true);
    }
}
