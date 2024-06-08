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
        $product = Product::factory()->create();
        $this->assertTrue(true);
    }
}
