<?php

namespace Modules\Order\Tests\controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderReceived;
use Modules\Order\Models\Order;
use Modules\Payment\PayBuddy;
use Modules\Product\Database\Factories\ProductFactory;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use DatabaseTransactions;


    public function test_it_successfully_creates_an_order(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $products = ProductFactory::new()->count(2)->create(
            new Sequence(
                [ 'name' => 'an expensive air fryer', 'price' => 10000, 'stock' => 10],
                [ 'name' => 'Iphone Pro Max 15', 'price' => 50000, 'stock' => 10],
            )
        );

        $token = PayBuddy::validToken();

        $response = $this->actingAs($user)
        ->post(route('order::order.checkout'), [
            'payment_token' => $token,
            'products' => [
                ['id' =>  $products->first()->id, 'quantity' => 1],
                ['id' =>  $products->last()->id, 'quantity' => 1],
            ],
        ]);

        $order = Order::query()->latest('id')->first();
        // dd($order->url());
        $response
        ->assertJson([
            'order_url' => $order->url(),
        ])
        ->assertStatus(201);

        Mail::assertSent(OrderReceived::class, function(OrderReceived $mail) use ($user){
            return $mail->hasTo($user->email);
        });

        // order
        $this->assertTrue($order->user->is($user));
        $this->assertEquals(60000, $order->total_in_piasters);
        $this->assertEquals('completed', $order->status);


        $payment = $order->lastPayment();
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('PayBuddy', $payment->payment_gateway);
        $this->assertEquals(36, strlen($payment->payment_id));
        $this->assertEquals(60000, $payment->total_in_piasters);
        $this->assertTrue($order->user->is($user));

        $this->assertCount(2, $order->lines);


        $products = $products->fresh();
        $this->assertEquals(9, $products->first()->stock);
        $this->assertEquals(9, $products->last()->stock);

        foreach ($products as $product) {
            $orderLine = $order->lines->where('product_id', $product->id)->first();
            $this->assertEquals($product->price, $orderLine->product_price_in_piasters);
            $this->assertEquals(1, $orderLine->quantity);
        }
    }

    public function test_it_fails_with_an_invalid_token(): void
    {
        $user  =  UserFactory::new()->create();
        $product  =  ProductFactory::new()->create();
        $token  =  PayBuddy::invalidToken();

         $response = $this->actingAs($user)
        ->postJson(route('order::order.checkout'), [
            'payment_token' => $token,
            'products' => [
                ['id' =>  $product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['payment_token']);

        $this->assertCount(0, Order::query()->get());
    }
}
