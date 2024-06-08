<?php

namespace Modules\Shipment\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Shipment\Models\Shipment;
use Tests\TestCase;

class ShipmentTest extends TestCase
{
    use DatabaseTransactions;


    public function test_it_creates_a_shipment()
    {
        $shipment = new Shipment();
        $shipment->order_id = 1;
        $shipment->provider = 'provider';
        $shipment->provider_shipment_id = rand(1,500);
        $shipment->save();
        $this->assertTrue(true);
    }
}
