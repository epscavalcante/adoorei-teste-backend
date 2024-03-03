<?php

use Core\Domain\Entities\Sale;
use Core\Domain\Entities\Product;
use Core\Domain\Entities\SaleProduct;
use Core\Application\Usecases\Sale\ListSaleUsecase;
use Core\Application\Usecases\Sale\ListSaleUsecaseOutput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;
use Core\Infra\Repositories\ProductMemoryRepository;
use Core\Infra\Repositories\SaleMemoryRepository;
use Core\Application\Usecases\Sale\SaleProductUsecaseOutput;

test('Deve retornar uma lista de vendas vazia', function () {
    $saleRepository = new SaleMemoryRepository();
    $productRepository = new ProductMemoryRepository();
    $usecase = new ListSaleUsecase(
        saleRepository: $saleRepository,
        productRepository: $productRepository
    );
    $output = $usecase->execute();
    expect($output)->toBeInstanceOf(ListSaleUsecaseOutput::class);
    expect($output->total)->toBe(0);
    expect($output->items)->toHaveCount(0);
    expect($output->items)->toMatchArray([]);
});

test('Deve retornar uma lista de produtos com produto', function () {
    $saleRepository = new SaleMemoryRepository();
    $productRepository = new ProductMemoryRepository();
    $usecase = new ListSaleUsecase(
        saleRepository: $saleRepository,
        productRepository: $productRepository
    );
    $product = Product::create('test name product', 'some description', 100);
    $productRepository->insert($product);
    $saleProduct = SaleProduct::create(
        productId: $product->getId()->getValue(),
        // name: $product->name,
        price: $product->getPrice(),
        amount: 1
    );
    $sale = Sale::create();
    $sale->addProduct($saleProduct);
    $saleRepository->insert($sale);

    $output = $usecase->execute();

    expect($output)->toBeInstanceOf(ListSaleUsecaseOutput::class);
    expect($output->total)->toBe(1);
    expect($output->items)->toHaveCount(1);
    expect($output->items[0])->toBeInstanceOf(SaleUsecaseOutput::class);
    expect($output->items[0]->saleId)->toBe($sale->getId()->getValue());
    expect($output->items[0]->total)->toBe(100);
    expect($output->items[0]->products)->toHaveCount(1);
    expect($output->items[0]->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
    expect($output->items[0]->products[0]->productId)->toBe($product->getId()->getValue());
    expect($output->items[0]->products[0]->name)->toBe($product->name);
    expect($output->items[0]->products[0]->price)->toBe($product->getPrice());
    expect($output->items[0]->products[0]->amount)->toBe(1);
});
