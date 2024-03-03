<?php

use App\Models\Product as ProductModel;
use App\Models\Sale as SaleModel;
use App\Models\SaleProduct as SaleProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use App\Repositories\Eloquent\SaleEloquentRepository;
use Core\Domain\Entities\Product;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);

describe('SaleEloquentIntegrationTest', function () {

    test('Insert method', function () {
        $saleRepository = new SaleEloquentRepository(
            new SaleModel()
        );
        $productRepository = new ProductEloquentRepository(
            new ProductModel()
        );

        $product = Product::create(
            name: 'Product test',
            description: 'Product test',
            price: 150
        );
        $sale = Sale::create();
        $sale->addProduct(SaleProduct::create(
            productId: $product->getId()->getValue(),
            // name: $product->name,
            price: $product->getPrice(),
            amount: 10
        ));
        $productRepository->insert($product);
        $saleRepository->insert($sale);

        expect($sale)->toBeInstanceOf(Sale::class);
        expect(SaleModel::count())->toBe(1);
        expect(SaleProductModel::count())->toBe(1);
        $saleModel = SaleModel::first()->toArray();
        expect($saleModel['id'])->toBe($sale->getId()->getValue());
        expect($saleModel['total'])->toBe($sale->getTotal());
    });

    test('List method', function () {
        $saleRepository = new SaleEloquentRepository(
            new SaleModel()
        );

        $product1 = Product::create(
            name: 'Product test',
            description: 'desc',
            price: 150
        );

        $product2 = Product::create(
            name: 'Product test',
            description: 'desc',
            price: 200
        );

        $sale = Sale::create();
        $sale->addProduct(
            SaleProduct::create(
                productId: $product1->getId(),
                // name: $product1->name,
                price: $product1->getPrice(),
                amount: 10
            )
        );
        $sale->addProduct(
            SaleProduct::create(
                productId: $product2->getId(),
                // name: $product2->name,
                price: $product2->getPrice(),
                amount: 7
            )
        );

        $saleRepository->insert($sale);

        expect(SaleModel::count())->toBe(1);
        expect(SaleProductModel::count())->toBe(2);
        expect(SaleModel::first()->toArray()['id'])->toBe($sale->getId()->getValue());
    });

    describe('Find method', function () {
        test('Should throw SaleNotFoundException', function () {
            $saleRepository = new SaleEloquentRepository(new SaleModel());
            $saleId = Uuid::create();

            $saleRepository->find($saleId);
        })->throws(SaleNotFoundException::class);

        test('Should finds a sale', function () {
            $sale = Sale::create();
            $saleRepository = new SaleEloquentRepository(new SaleModel());
            $saleRepository->insert($sale);

            $saleFound = $saleRepository->find($sale->getId());
            expect($saleFound)->toBeInstanceOf(Sale::class);
            expect(
                $sale->getId()->equals($saleFound->getId())
            )->toBe(true);
        });
    });
});
