<?php

namespace Modules\Product;

use Modules\Product\Models\Product;

readonly class ProductDto
{
    public function __construct(
        public int $id,
        public int $priceInPiasters,
        public int $unitsInStock,
    ) {

    }

    public static function fromEloquentModel(Product $product): self
    {
        return new self(
            id: $product->id,
            priceInPiasters: $product->price,
            unitsInStock: $product->stock,
        );
    }

}
