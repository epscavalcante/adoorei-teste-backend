<?php

use Core\Domain\Entities\Product;
use Core\Application\Usecases\Sale\FindSaleUsecase;
use Core\Application\Usecases\Sale\FindSaleUsecaseInput;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Infra\Repositories\SaleMemoryRepository;
use Core\Domain\ValueObjects\Uuid;
use Core\Infra\Repositories\ProductMemoryRepository;
use Core\Application\Usecases\Sale\SaleProductUsecaseOutput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;

describe('FindSaleUsecaseUnitTest', function () {
    test('Should throws SaleNotFoundException', function () {
        $saleRepository = new SaleMemoryRepository();
        $productRepository = new ProductMemoryRepository();
        $usecase = new FindSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new FindSaleUsecaseInput((Uuid::create())->getValue());
        $usecase->execute($input);
    })->throws(SaleNotFoundException::class);

    test('Should return a saled found', function () {
        $productRepository = new ProductMemoryRepository();
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
        $saleRepository = new SaleMemoryRepository();
        $saleRepository->insert($sale);

        $usecase = new FindSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new FindSaleUsecaseInput($sale->getId()->getValue());
        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->toBe($sale->getId()->getValue());
        expect($output->total)->toBe(100);
        expect($output->products)->toHaveCount(1);
        expect($output->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
        expect($output->products[0]->productId)->toBe($product->getId()->getValue());
        expect($output->products[0]->name)->toBe($product->name);
        expect($output->products[0]->price)->toBe($product->getPrice());
        expect($output->products[0]->amount)->toBe(1);
        expect($output->products[0]->total)->toBe(100);
    });
});
