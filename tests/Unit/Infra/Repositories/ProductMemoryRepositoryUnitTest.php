<?php

use Core\Domain\Entities\Product;
use Core\Domain\ValueObjects\Uuid;
use Core\Infra\Repositories\ProductMemoryRepository;

describe('ProductMemoryRepositoryUnitTest', function () {

    test('Insert method', function () {
        $productRepository = new ProductMemoryRepository();

        $product = Product::create(
            name: 'Product test',
            description: 'desc',
            price: 150
        );
        $productRepository->insert($product);

        expect($productRepository->getItems())->toHaveCount(1);
    });

    describe('List method', function () {
        $productRepository = new ProductMemoryRepository();

        test('Scenarios: ', function (array $items, int $count) use ($productRepository) {
            if (count($items)) {
                foreach ($items as $item) {
                    $productRepository->insert($item);
                }
            }

            $sales = $productRepository->list();
            expect($sales)->toHaveCount($count);
        })->with([
            'empty list' => [[], 0],
            'with one item' => [
                [
                    Product::create(
                        name: 'Product test',
                        description: 'desc',
                        price: 150
                    ),
                ],
                1,
            ],
            'with some items' => [
                [
                    Product::create(
                        name: 'Product test',
                        description: 'desc',
                        price: 150
                    ),
                    Product::create(
                        name: 'Product test',
                        description: 'desc',
                        price: 150
                    ),
                    Product::create(
                        name: 'Product test',
                        description: 'desc',
                        price: 150
                    ),
                ],
                4,
            ],
        ]);
    });

    describe('Exists method', function () {

        test('Should return empty result', function () {
            $productRepository = new ProductMemoryRepository();

            $result = $productRepository->existsByIds([]);
            expect($result['exists'])->toHaveCount(0);
            expect($result['notExists'])->toHaveCount(0);
        });

        test('Should return all exists', function () {
            $firstProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $secondProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $thirdProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $productRepository = new ProductMemoryRepository();
            $productRepository->insert($firstProduct);
            $productRepository->insert($secondProduct);
            $productRepository->insert($thirdProduct);
            $result = $productRepository->existsByIds(
                productIds: [
                    $firstProduct->getId(),
                    $secondProduct->getId(),
                    $thirdProduct->getId(),
                ]
            );

            expect($result['exists'])->toHaveCount(3);
            expect($result['notExists'])->toHaveCount(0);
        });

        test('Should return result with exists and not exists', function () {
            $firstProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $secondProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $thirdProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $productRepository = new ProductMemoryRepository();
            $productRepository->insert($firstProduct);
            $productRepository->insert($secondProduct);
            $productRepository->insert($thirdProduct);
            $result = $productRepository->existsByIds(
                productIds: [
                    Uuid::create(),
                    $firstProduct->getId(),
                    $secondProduct->getId(),
                    $thirdProduct->getId(),
                ]
            );

            expect($result['exists'])->toHaveCount(3);
            expect($result['notExists'])->toHaveCount(1);
        });
    });

    describe('FindByIds method', function () {

        test('Should return empty result', function () {
            $productRepository = new ProductMemoryRepository();

            $result = $productRepository->findByIds([]);
            expect($result)->toHaveCount(0);
        });

        test('Should return some products exists', function () {
            $firstProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $secondProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $thirdProduct = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );
            $productRepository = new ProductMemoryRepository();
            $productRepository->insert($firstProduct);
            $productRepository->insert($secondProduct);
            $productRepository->insert($thirdProduct);
            $result = $productRepository->findByIds(
                productIds: [
                    // $firstProduct->getId(),
                    $secondProduct->getId(),
                    $thirdProduct->getId(),
                ]
            );

            expect($result)->toHaveCount(2);
            expect($result[0]->getId())->toBeInstanceOf(Uuid::class);
            expect($result[1]->getId())->toBeInstanceOf(Uuid::class);
            expect($result[0]->getId()->getValue())->toBe($secondProduct->getId()->getValue());
            expect($result[1]->getId()->getValue())->toBe($thirdProduct->getId()->getValue());
        });
    });
});
