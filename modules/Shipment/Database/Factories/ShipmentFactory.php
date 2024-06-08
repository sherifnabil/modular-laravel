<?php

namespace Modules\Shipment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipment\Models\Shipment;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'order_id' => rand(1,500),
            'provider' => $this->faker->word(),
            'provider_shipment_id' => rand(1,500),
        ];
    }
}
