<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;

describe('PATCH /sales/id/cancel', function () {
    test('Shoud throw UuidInvalidException, receives BadRequest', function () {
        $this->patchJson(route('sales.cancel', 'fake'))
            ->assertBadRequest();
    });

    test('Shoud throw EntityNotFound, receives 404 NotFound', function () {
        $this->patchJson(route('sales.cancel', '84705615-39a9-49ae-8bf6-2ba2157c7836'))
            ->assertNotFound();
    });

    test('Shoud throw SaleAlreadBeCancelledException, receives 400 BadRequest', function () {
        $sale = Sale::factory()->create(['status' => 'cancelled']);
        $this->patchJson(route('sales.cancel', $sale->id))
            ->assertBadRequest();
    });

    test('Shoud cancel a sale', function () {
        $product = Product::factory()->create(['name' => 'test']);
        $sale = Sale::factory()->create(['status' => 'opened', 'total' => 300]);
        SaleProduct::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'amount' => 3,
            'price' => 100,
            'total' => 300
        ]);
        $this->patchJson(route('sales.cancel', $sale->id))
            ->assertNoContent();

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'status' => 'cancelled'
        ]);
    });
});
