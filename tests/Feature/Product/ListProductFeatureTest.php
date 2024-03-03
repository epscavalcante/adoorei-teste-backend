<?php

use App\Models\Product;

describe('ProducApiFeatureTest', function () {

    describe('GET /products', function () {
        test('Shoud return empty list of products', function () {
            $this->getJson(route('products.list'))
                ->assertStatus(200);
        });

        test('Shoud return a list of products', function () {
            Product::factory(5)->create();
            $this->getJson(route('products.list'))
                ->assertStatus(200)
                ->assertJsonCount(5, 'data')
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'price',
                        ],
                    ],
                ]);
        });
    });
});
