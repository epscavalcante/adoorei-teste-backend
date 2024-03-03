<?php

use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\ValueObjects\Uuid;
use Core\Infra\Repositories\SaleMemoryRepository;

describe('SaleMemoryRepositoryUnitTest', function () {

    test('Insert method', function () {
        $sale = Sale::create();
        $saleRepository = new SaleMemoryRepository();
        $saleRepository->insert($sale);

        expect($sale)->toBeInstanceOf(Sale::class);
        expect($saleRepository->getItems())->toHaveCount(1);
    });

    describe('List method', function () {
        $saleRepository = new SaleMemoryRepository();

        test('Scenarios: ', function ($items, $count) use ($saleRepository) {
            if (count($items)) {
                foreach ($items as $item) {
                    $saleRepository->insert($item);
                }
            }

            $sales = $saleRepository->list();
            expect($sales)->toHaveCount($count);
        })->with([
            'empty list' => [[], 0],
            'with one item' => [[Sale::create()], 1],
            'with some items' => [
                [
                    Sale::create(),
                    Sale::create(),
                    Sale::create(),
                ],
                4,
            ],
        ]);
    });

    describe('Find method', function () {

        test('Should throw SaleNotFoundException', function () {
            $saleRepository = new SaleMemoryRepository();
            $saleId = Uuid::create();

            $saleRepository->find($saleId);
        })->throws(SaleNotFoundException::class);

        test('Should finds a sale', function () {
            $sale = Sale::create();
            $saleRepository = new SaleMemoryRepository();
            $saleRepository->insert($sale);

            $saleFound = $saleRepository->find($sale->getId());
            expect($saleFound)->toBeInstanceOf(Sale::class);
            expect(
                $sale->getId()->equals($saleFound->getId())
            )->toBe(true);
        });
    });

    describe('Update method', function () {

        test('Should throw SaleNotFoundException', function () {
            $saleRepository = new SaleMemoryRepository();
            $sale = Sale::create();

            $saleRepository->update($sale);
        })->throws(SaleNotFoundException::class);

        test('Should update a sale', function () {
            $sale = Sale::create();
            $saleRepository = new SaleMemoryRepository();
            $saleRepository->insert($sale);

            $sale->addProduct(SaleProduct::create(
                productId: Uuid::create()->getValue(),
                price: 100,
                amount: 1
            ));

            $saleRepository->update($sale);

            $saleUpdate = $saleRepository->find($sale->getId());

            expect($saleUpdate)->toBeInstanceOf(Sale::class);
            expect(
                $sale->getId()->equals($saleUpdate->getId())
            )->toBe(true);
        });
    });
});
