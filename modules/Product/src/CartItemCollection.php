<?php

namespace Modules\Product;

use Illuminate\Support\Collection;
use Modules\Product\Models\Product;

class CartItemCollection
{
    public function __construct(
        public Collection $items,
    ) {
    }

    public static function fromCheckoutData(array $data): self
    {
        $cartItems = collect($data)->map(
            fn($productDetails) =>
            new CartItem(
                ProductDto::fromEloquentModel(Product::find($productDetails['id'])),
                $productDetails['quantity']
            )
        );
        return new self($cartItems);
    }

    public function totalInPiasters(): int
    {
        return $this->items->sum(
            fn (CartItem $cartItem) =>
            $cartItem->quantity * $cartItem->product->priceInPiasters
        );
    }

    /**
     *
     * @return Collection <CartItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }
}
