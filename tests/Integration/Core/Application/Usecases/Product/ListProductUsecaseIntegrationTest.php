<?php

use App\Models\Product as ProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Application\Usecases\Product\ListProductUsecase;
use Core\Application\Usecases\Product\ListProductUsecaseOutput;
use Core\Application\Usecases\Product\ProductUsecaseOutput;
use Core\Domain\Entities\Product;

test('Deve retornar uma lista de produtos vazia', function () {
    $repository = new ProductEloquentRepository(new ProductModel());
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
    $repository = new ProductEloquentRepository(new ProductModel());
    $product = Product::create(
        name: 'test name product',
        description: 'some description',
        price: 100,
    );
    $repository->insert($product);
    $usecase = new ListProductUsecase(
        productRepository: $repository
    );
    $output = $usecase->execute();

    expect($output)->toBeInstanceOf(ListProductUsecaseOutput::class);
    expect($output->total)->toBe(1);
    expect($output->items)->toHaveCount(1);
    expect($output->items[0])->toBeInstanceOf(ProductUsecaseOutput::class);
});
