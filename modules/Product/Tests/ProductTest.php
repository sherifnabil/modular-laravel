<?php

namespace Modules\Product\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Product\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;


    public function test_it_creates_an_product()
    {
        $product = new Product();
        $product->name = 'Product Name';
        $product->price = 1;
        $product->stock = 1;
        $product->save();
        $this->assertTrue(true);
    }
}
