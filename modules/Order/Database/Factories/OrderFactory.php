<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {

        return [
            'user_id' => 1,
            'total_in_piasters' => rand(1,500),
            'payment_gateway' => 'paypal',
            'status' => 'pending',
            'payment_id' => rand(1,500),
        ];
    }
}
