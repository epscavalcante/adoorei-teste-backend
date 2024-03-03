<?php

use Core\Application\Usecases\Product\ListProductUsecase;
use Core\Application\Usecases\Product\ListProductUsecaseOutput;
use Core\Application\Usecases\Product\ProductUsecaseOutput;
use Core\Domain\Entities\Product;
use Core\Infra\Repositories\ProductMemoryRepository;

describe('ListProductUsecaseUnitTest', function () {

    test('Deve retornar uma lista de produtos vazia', function () {
        $repository = new ProductMemoryRepository();
        $usecase = new ListProductUsecase(
            productRepository: $repository
        );
        $output = $usecase->execute();

        expect($output)->toBeInstanceOf(ListProductUsecaseOutput::class);
        expect($output->total)->toBe(0);
        expect($output->items)->toHaveCount(0);
        expect($output->items)->toMatchArray([]);
    });

    test('Deve retornar uma lista de produtos com produto', function () {
        $product = Product::create(
            name: 'test name product',
            description: 'some description',
            price: 100,
        );
        $repository = new ProductMemoryRepository();
        $repository->insert($product);
        $usecase = new ListProductUsecase(
            productRepository: $repository
        );
        $output = $usecase->execute();

        expect($output)->toBeInstanceOf(ListProductUsecaseOutput::class);
        expect($output->total)->toBe(1);
        expect($output->items)->toHaveCount(1);
        expect($output->items[0])->toBeInstanceOf(ProductUsecaseOutput::class);
        expect($output->items[0]->productId)->toBe($product->getId()->getValue());
        expect($output->items[0]->name)->toBe('test name product');
        expect($output->items[0]->description)->toBe('some description');
        expect($output->items[0]->price)->toBe(100);
    });
});
