<?php

use Core\Domain\Entities\Product;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleCantBeCancelledException;
use Core\Domain\SaleStatusEnum;
use Core\Domain\ValueObjects\Price;
use Core\Domain\ValueObjects\Uuid;

describe('Sale Unit Test', function () {
    test('Deve criar um venda com id e lista de produtos vazia', function () {
        $sale = Sale::create();
        expect($sale)->toBeInstanceOf(Sale::class);
        expect($sale->getId())->toBeInstanceOf(Uuid::class);
        expect($sale->getProducts())->toHaveCount(0);
        expect($sale->getTotal())->toBe(0);
        expect($sale->getStatus())->toBeInstanceOf(SaleStatusEnum::class);
        expect($sale->getStatus()->value)->toBe(SaleStatusEnum::OPENED->value);
    });

    test('Deve restaurar uma venda', function () {
        $sale = Sale::create();
        expect($sale)->toBeInstanceOf(Sale::class);
        expect($sale->getId())->toBeInstanceOf(Uuid::class);
        expect($sale->getProducts())->toHaveCount(0);
        expect($sale->getTotal())->toBe(0);
        expect($sale->getStatus())->toBeInstanceOf(SaleStatusEnum::class);
        expect($sale->getStatus()->value)->toBe(SaleStatusEnum::OPENED->value);
    });

    describe('mark as cancelled method', function () {
        test('Deve cancelar uma venda', function () {
            $sale = Sale::create();
            $sale->markAsCancelled();
            expect($sale->getStatus())->toBeInstanceOf(SaleStatusEnum::class);
            expect($sale->getStatus()->value)->toBe(SaleStatusEnum::CANCELLED->value);
        });

        test('Should throws error when cancel a sale cancelled', function () {
            $sale = Sale::restore(
                saleId: '29b761f7-316f-4453-9863-75a0a646a8a0',
                status: SaleStatusEnum::CANCELLED->value,
                total: 0,
                products: []
            );
            $sale->markAsCancelled();
        })->throws(SaleCantBeCancelledException::class, "The sale (29b761f7-316f-4453-9863-75a0a646a8a0) can't be cancelled.");
    });

    describe('Restore method', function () {

        test('Deve restaurar uma venda com produtos vazio', function () {
            $saleId = Uuid::create();
            $sale = Sale::restore(
                saleId: $saleId->getValue(),
                total: 0,
                status: 'opened',
                products: []
            );
            expect($sale)->toBeInstanceOf(Sale::class);
            expect($sale->getId())->toBeInstanceOf(Uuid::class);
            expect($sale->getId()->getValue())->toBe($saleId->getValue());
            expect($sale->getProducts())->toHaveCount(0);
            expect($sale->getTotal())->toBe(0);
            expect($sale->getStatus())->toBeInstanceOf(SaleStatusEnum::class);
            expect($sale->getStatus()->value)->toBe(SaleStatusEnum::OPENED->value);
        });
    });

    test('Deve criar um venda com lista de produtos', function () {
        $sale1 = Sale::create();
        $sale1->addProduct(
            new SaleProduct(
                productId: Uuid::create(),
                // name: 'Test',
                price: new Price(1000),
                amount: 2
            )
        );
        expect($sale1)->toBeInstanceOf(Sale::class);
        expect($sale1->getId())->toBeInstanceOf(Uuid::class);
        expect($sale1->getProducts())->toHaveCount(1);
        expect($sale1->getTotal())->toBe(2000);

        $product = Product::create('product 1', 'desc', 1000);
        $product2 = Product::create('product 2', 'desc', 500);
        $sale2 = Sale::create();
        $sale2->addProduct(
            new SaleProduct(
                productId: $product->getId(),
                // name: 'test',
                price: new Price($product->getPrice()),
                amount: 2
            )
        );
        $sale2->addProduct(new SaleProduct(
            productId: $product2->getId(),
            // name: 'test',
            price: new Price($product2->getPrice()),
            amount: 4
        ));
        expect($sale2)->toBeInstanceOf(Sale::class);
        expect($sale1->getId())->toBeInstanceOf(Uuid::class);
        expect($sale2->getProducts())->toHaveCount(2);
        expect($sale2->getTotal())->toBe(4000);
    });

    test('Deve adicionar um produto na venda', function () {
        $sale = Sale::create();
        $product = Product::create('product 1', 'desc', 1000);
        $saleProduct = new SaleProduct(
            productId: $product->getId(),
            // name: 'test',
            price: new Price($product->getPrice()),
            amount: 2
        );
        $sale->addProduct($saleProduct);
        expect($sale)->toBeInstanceOf(Sale::class);
        expect($sale->getId())->toBeInstanceOf(Uuid::class);
        expect($sale->getProducts())->toHaveCount(1);
        expect($sale->getTotal())->toBe(2000);
    });

    test('Deve incrementar o amount quando inserir um produto que existe na lista de produtos da venda', function () {
        $product = Product::create('product 1', 'desc', 1000);
        $saleProduct = SaleProduct::create(
            productId: $product->getId(),
            // name: 'test',
            price: $product->getPrice(),
            amount: 2
        );
        $sale = Sale::create();
        $sale->addProduct($saleProduct);
        $sale->addProduct($saleProduct);

        expect($sale)->toBeInstanceOf(Sale::class);
        expect($sale->getId())->toBeInstanceOf(Uuid::class);
        expect($sale->getProducts())->toHaveCount(1);
        expect($sale->getProducts()[0])->toBeInstanceOf(SaleProduct::class);
        expect($sale->getProducts()[0]->getAmount())->toBe(4);
        expect($sale->getTotal())->toBe(4000);
    });

    describe('syncProducts method', function () {

        test('Should sync products', function () {
            $sale = Sale::create();
            expect($sale->getTotal())->toBe(0);

            $product1 = SaleProduct::create(
                productId: Uuid::create()->getValue(),
                // name: 'test',
                amount: 1,
                price: 100
            );
            $product2 = SaleProduct::create(
                productId: Uuid::create()->getValue(),
                // name: 'test',
                amount: 2,
                price: 300
            );

            $sale->syncProducts([$product1, $product2]);

            expect($sale->getTotal())->toBe(700);
        });
    });
});
