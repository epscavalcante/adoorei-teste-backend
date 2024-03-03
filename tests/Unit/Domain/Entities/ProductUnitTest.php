<?php

use Core\Domain\Entities\Product;
use Core\Domain\Exceptions\PriceNegativeValueException;
use Core\Domain\ValueObjects\Uuid;

describe('Product Unit Test', function () {

    test('Deve criar um produto', function () {
        $product = Product::create(
            name: 'name',
            description: 'description',
            price: 100
        );
        expect($product)->toBeInstanceOf(Product::class);
        expect($product->getId())->toBeInstanceOf(Uuid::class);
        expect($product->name)->toBe('name');
        expect($product->description)->toBe('description');
        expect($product->getPrice())->toBe(100);
    });

    test('Deve invalidar criação de produto com valor negaivo', function () {
        Product::create(
            name: 'name',
            description: 'description',
            price: -100
        );
    })->throws(PriceNegativeValueException::class);
});
