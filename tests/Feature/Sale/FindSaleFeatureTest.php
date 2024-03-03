<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;

describe('GET /sales/id', function () {
    test('Shoud throw UuidInvalidException, receives BadRequest', function () {
        $this->getJson(route('sales.show', 'fake'))
            ->assertBadRequest();
    });

    test('Shoud throw EntityNotFound, receives 404 NotFound', function () {
        $this->getJson(route('sales.show', '84705615-39a9-49ae-8bf6-2ba2157c7836'))
            ->assertNotFound();
    });

    test('Shoud finds a sale', function () {
        $product = Product::factory()->create(['name' => 'test']);
        $sale = Sale::factory()->create(['status' => 'opened', 'total' => 300]);
        SaleProduct::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'amount' => 3,
            'price' => 100,
            'total' => 300,
        ]);
        $this->getJson(route('sales.show', $sale->id))
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJson([
                'data' => [
                    'id' => $sale->id,
                    'total' => 300,
                    'status' => 'opened',
                    'products' => [
                        [
                            'id' => $product->id,
                            'name' => 'test',
                            'price' => 100,
                            'amount' => 3,
                        ],
                    ],
                ],
            ]);
    });
});
