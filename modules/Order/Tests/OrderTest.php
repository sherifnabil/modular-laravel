<?php

namespace Modules\Order\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Order\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;


    public function test_it_creates_an_order()
    {
        $order = Order::factory()->create();
        $this->assertTrue(true);
    }
}
