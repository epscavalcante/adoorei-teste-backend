<?php

use App\Models\Product;
use Core\Domain\ValueObjects\Uuid;

describe('POST /sales', function () {
    test('Shoud throw Invalid data, receives EntityUnprocessable invalid data', function () {
        $this->postJson(route('sales.store'), [])
            ->assertUnprocessable();
    });

    test('Shoud throw Invalid data, receives EntityValidationException because product not exist', function () {
        $body = [
            'products' => [
                [
                    'productId' => Uuid::create()->getValue(),
                    'price' => 21,
                    'amount' => 1312
                ]
            ]
        ];

        $this->postJson(route('sales.store'), $body)
            ->assertUnprocessable();
    });

    test('Shoud creates a sale', function () {
        $product = Product::factory()->create(['price' => 100]);
        $body = [
            'products' => [
                [
                    'productId' => $product->id,
                    'price' => $product->price,
                    'amount' => 10
                ]
            ]
        ];

        $this->postJson(route('sales.store'), $body)
            ->assertCreated()
            ->assertJsonStructure([
                'id',
                'total',
                'status',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'amount'
                    ]
                ]
            ]);
    });
});
