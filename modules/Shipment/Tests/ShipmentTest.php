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
        $shipment = Shipment::factory()->create();
        $this->assertTrue(true);
    }
}
