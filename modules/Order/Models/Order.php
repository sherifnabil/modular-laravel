<?php

namespace Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Exceptions\OrderMissiongOrderLinesException;
use Modules\Payment\Payment;
use Modules\Product\CartItemCollection;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_in_piasters',
        'payment_gateway',
        'payment_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_in_piasters' => 'integer',
    ];


    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class, 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lastPayment()
    {
        return $this->payments()->latest()->first();
    }

    public function url(): string
    {
        return route('order::order.show', $this->id);
    }

    public static function startForUser(int $userId): self
    {
        return self::make([
            'user_id' => $userId,
            'status'  => self::PENDING
        ]);
    }

    /**
     *
     * @param CartItemCollection <CartItem> $items
     * @return void
     */
    public function addLinesFromCartItems(CartItemCollection $items): void
    {
        foreach ($items->items() as $cartItem) {
            $this->lines->push(
                OrderLine::make([
                    'product_id' => $cartItem->product->id,
                    'product_price_in_piasters' => $cartItem->product->priceInPiasters,
                    'quantity' => $cartItem->quantity,
                ])
            );
        }

        $this->total_in_piasters = $this->lines->sum(fn($line) => $line->product_price_in_piasters);
    }

    /**
     * @throws OrderMissiongOrderLinesException
     *
     * @return void
     */
    public function fullfill(): void
    {
        if($this->lines->isEmpty()) {
            throw new OrderMissiongOrderLinesException();
        }

        $this->status = self::COMPLETED;
        $this->save();
        $this->lines()->saveMany($this->lines);
    }
}
