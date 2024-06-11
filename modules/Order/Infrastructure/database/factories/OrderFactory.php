<?php

namespace Modules\Order\Infrastructure\database\factories;

use Modules\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

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
