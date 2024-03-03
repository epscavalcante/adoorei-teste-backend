<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;

describe('ProducApiFeatureTest', function () {
    describe('GET /sales', function () {
        test('Shoud return empty list of sales', function () {
            $this->getJson(route('sales.list'))
                ->assertStatus(200);
        });

        test('Shoud return a list of sales', function () {
            Product::factory(4)->create();
            Sale::factory(2)->create();
            SaleProduct::factory(2)->create();
            $body = $this->getJson(route('sales.list'));
            $body->assertStatus(200)
                ->assertJsonCount(2, 'data')
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'total',
                            'status',
                            'products' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'price',
                                    'amount',
                                ],
                            ],
                        ],
                    ],
                ]);
        });
    });
});
