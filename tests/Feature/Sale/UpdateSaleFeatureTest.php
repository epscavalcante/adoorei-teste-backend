<?php

use App\Models\Product;
use App\Models\Sale;
use Core\Domain\ValueObjects\Uuid;

describe('PUT /sales/id/products', function () {
    test('Shoud throw Invalid data, receives EntityUnprocessable invalid data', function () {
        $this->putJson(route('sales.update_products', 'fake'), [])
            ->assertUnprocessable();
    });

    test('Shoud throw UuidInvalidException, receives BadRequest', function () {
        $this->putJson(
            route('sales.update_products', 'fake'),
            [
                'products' => [
                    [
                        'productId' => Uuid::create()->getValue(),
                        'price' => 21,
                        'amount' => 1312
                    ]
                ]
            ]
        )
            ->assertBadRequest();
    });

    test('Shoud throw EntityNotFound, receives 404 NotFound', function () {
        $this->putJson(
            route('sales.update_products', '84705615-39a9-49ae-8bf6-2ba2157c7836'),
            [
                'products' => [
                    [
                        'productId' => Uuid::create()->getValue(),
                        'price' => 21,
                        'amount' => 1312
                    ]
                ]
            ]
        )
            ->assertNotFound();
    });

    test('Shoud throw Invalid data, receives EntityValidationException because product not exist', function () {
        $sale = Sale::factory()->create(['status' => 'opened', 'total' => 300]);
        $body = [
            'products' => [
                [
                    'productId' => Uuid::create()->getValue(),
                    'price' => 21,
                    'amount' => 1312
                ]
            ]
        ];

        $this->putJson(route('sales.update_products', $sale->id), $body)
            ->assertUnprocessable();
    });

    test('Shoud throw SaleAlreadBecanceled, receives EntityValidationException because product not exist', function () {
        $sale = Sale::factory()->create(['status' => 'cancelled']);
        $body = [
            'products' => [
                [
                    'productId' => Uuid::create()->getValue(),
                    'price' => 21,
                    'amount' => 1312
                ]
            ]
        ];

        $this->putJson(
            route('sales.update_products', $sale->id),
            $body
        )
            ->assertBadRequest()
            ->assertJson([
                'message' => "The sale ({$sale->id}) already be cancelled."
            ]);
    });

    test('Shoud update products of sale', function () {
        $product = Product::factory()->create(['name' => 'test', 'price' => 100]);
        $sale = Sale::factory()->create(['status' => 'opened']);
        $body = [
            'products' => [
                [
                    'productId' => $product->id,
                    'price' => $product->price,
                    'amount' => 10
                ]
            ]
        ];

        $this->putJson(route('sales.update_products', $sale->id), $body)
            ->assertOk()

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
            ])
            ->assertJson([
                'id' => $sale->id,
                'total' => 1000,
                'status' => 'opened',
                'products' => [
                    [
                        'id' => $product->id,
                        'name' => 'test',
                        'price' => 100,
                        'amount' => 10
                    ]
                ]
            ]);
    });
});
