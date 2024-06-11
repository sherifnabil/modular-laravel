<?php

namespace Modules\Product\Warehouse;

use Modules\Product\Models\Product;

class ProductStockManger
{
    public function decrement(int $productId, int $amount): void
    {
        $product = Product::find($productId)?->decrement('stock', $amount);
    }
}
