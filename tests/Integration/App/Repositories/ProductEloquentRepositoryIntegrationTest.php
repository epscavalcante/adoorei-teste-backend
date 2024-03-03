<?php

use App\Models\Product as ProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Domain\Entities\Product;
use Core\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

uses(DatabaseMigrations::class);

describe('ProductEloquentRepository Integration tests', function () {

    describe('Insert method', function () {
        test('Show insert a product', function () {
            $productRepository = new ProductEloquentRepository(
                new ProductModel()
            );

            $product = Product::create(
                name: 'Product test',
                description: 'desc',
                price: 150
            );

            $productInserted = $productRepository->insert($product);
            expect($productInserted)->toBeInstanceOf(Product::class);
            expect(ProductModel::count())->toBe(1);
            expect(ProductModel::first()->toArray()['id'])->toBe($product->getId()->getValue());
        });
    });

    describe('Exists method', function () {

        test('Should return empty result', function () {
            $productRepository = new ProductEloquentRepository(new ProductModel());

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
            $productRepository = new ProductEloquentRepository(new ProductModel());
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
            $productRepository = new ProductEloquentRepository(new ProductModel());
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
            $productRepository = new ProductEloquentRepository(new ProductModel());

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
            $productRepository = new ProductEloquentRepository(new ProductModel());
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
        });
    });
});
