<?php

namespace Modules\Order\Tests\Checkout;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Order\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Checkout\PaymentFailed;
use Modules\Order\Checkout\Contracts\UserDto;
use Modules\Order\Checkout\Contracts\OrderDto;
use Modules\Order\Checkout\PaymentForOrderFailed;
use Modules\Order\Checkout\NotifyUserOfPaymentFailure;

class NotifyUserOfPaymentFailureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_notifies_the_user_of_payment_failure(): void
    {
        Mail::fake();

        $order = Order::factory()->create();
        $orderDto = OrderDto::fromEloquentModel($order);
        $userDto = UserDto::fromEloquentModel(User::factory()->create());

        $event = new PaymentFailed($userDto, $orderDto, 'payment failed');

        app(NotifyUserOfPaymentFailure::class)->handle($event);

        Mail::assertSent(PaymentForOrderFailed::class, fn(PaymentForOrderFailed $mail) => $mail->hasTo($userDto->email));
    }
}
