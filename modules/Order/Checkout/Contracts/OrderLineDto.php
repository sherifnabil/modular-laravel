<?php

namespace Modules\Order\Checkout\Contracts;

use Modules\Order\OrderLine;
use Illuminate\Database\Eloquent\Collection;

readonly class OrderLineDto
{
    public function __construct(
        public int $productId,
        public int $productPriceInPiasters,
        public int $quantity
    ) {
    }

    public static function fromEloquentModel(OrderLine $line): self
    {
        return new self($line->product_id, $line->product_price_in_piasters, $line->quantity);
    }

    public static function fromEloquentCollection(Collection $lines): array
    {
        return $lines->map(fn(OrderLine $line) => self::fromEloquentModel($line))->all();
    }
}
