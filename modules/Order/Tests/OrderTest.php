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
        $order = new Order();
        $order->user_id = 1;
        $order->total_in_cents = 1;
        $order->status = 'pending';
        $order->payment_gateway = 'paypal';
        $order->payment_id = rand(1,500);
        $order->save();
        $this->assertTrue(true);
    }
}
