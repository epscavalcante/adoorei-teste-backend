<?php

use Core\Application\Validations\ProductIdsExistsValidation;
use Core\Infra\Repositories\ProductMemoryRepository;
use Core\Application\Usecases\Sale\UpdateSaleUsecase;
use Core\Application\Usecases\Sale\UpdateSaleUsecaseInput;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\Product;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Infra\Repositories\SaleMemoryRepository;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\ValueObjects\Uuid;
use Core\Application\Usecases\Sale\SaleProductUsecaseOutput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;

describe('UpdateSaleUsecase Unit Test', function () {
    test('Should throws SaleNotFoundException', function () {
        $productRepository = new ProductMemoryRepository();
        $saleRepository = new SaleMemoryRepository();
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new UpdateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $input = new UpdateSaleUsecaseInput(
            saleId: Uuid::create()->getValue(),
            products: []
        );

        $usecase->execute($input);
    })->throws(SaleNotFoundException::class);

    test('Should throws SaleAlreadByCancelledFoundException', function () {
        $productRepository = new ProductMemoryRepository();
        $saleRepository = new SaleMemoryRepository();
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new UpdateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $sale->markAsCancelled();
        $saleRepository->insert($sale);
        $input = new UpdateSaleUsecaseInput(
            saleId: $sale->getId()->getValue(),
            products: []
        );

        $usecase->execute($input);
    })->throws(SaleAlreadBeCancelledException::class);

    test('Should throws EntityValidationException because product not exist', function () {
        $saleRepository = new SaleMemoryRepository();
        $productRepository = new ProductMemoryRepository();
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new UpdateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $saleRepository->insert($sale);
        $input = new UpdateSaleUsecaseInput(
            saleId: $sale->getId()->getValue(),
            products: [
                [
                    'productId' => Uuid::create()->getValue(),
                    'name' => 'Product 1',
                    'price' => 1500,
                    'amount' => 2
                ]
            ]
        );

        $usecase->execute($input);
    })->throws(EntityValidationException::class);


    test('Should update sale add product', function () {
        $product = Product::create(
            name: 'Product 1',
            description: 'Product 1',
            price: 1500,
        );
        $productRepository = new ProductMemoryRepository();
        $productRepository->insert($product);
        $saleRepository = new SaleMemoryRepository();
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new UpdateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $saleRepository->insert($sale);

        $input = new UpdateSaleUsecaseInput(
            saleId: $sale->getId()->getValue(),
            products: [
                [
                    'productId' => $product->getId()->getValue(),
                    'name' => $product->name,
                    'price' => $product->getPrice(),
                    'amount' => 2
                ]
            ]
        );

        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->toBe($sale->getId()->getValue());
        expect($output->total)->toBe(3000);
        expect($output->products)->toHaveCount(1);
        expect($output->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
        expect($output->products[0]->productId)->toBe($product->getId()->getValue());
        expect($output->products[0]->name)->toBe($product->name);
        expect($output->products[0]->price)->toBe(1500);
        expect($output->products[0]->amount)->toBe(2);
        expect($output->products[0]->total)->toBe(3000);
    });

    test('Should update sale remove product', function () {
        $product1 = Product::create(
            name: 'Product 1',
            description: 'Product 1',
            price: 1500,
        );
        $product2 = Product::create(
            name: 'Product 2',
            description: 'Product 2',
            price: 345,
        );
        $productRepository = new ProductMemoryRepository();
        $productRepository->insert($product1);
        $productRepository->insert($product2);
        $saleRepository = new SaleMemoryRepository();
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new UpdateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $sale->addProduct(
            SaleProduct::create(
                productId: $product1->getId()->getValue(),
                // name: $product1->name,
                amount: 1,
                price: $product1->getPrice(),
            )
        );
        $sale->addProduct(
            SaleProduct::create(
                productId: $product2->getId()->getValue(),
                // name: $product2->name,
                amount: 1,
                price: $product2->getPrice(),
            )
        );
        $saleRepository->insert($sale);

        $input = new UpdateSaleUsecaseInput(
            saleId: $sale->getId()->getValue(),
            products: [
                [
                    'productId' => $product1->getId()->getValue(),
                    // 'name' => $product1->name,
                    'price' => 1500,
                    'amount' => 3
                ]
            ]
        );

        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->toBe($sale->getId()->getValue());
        expect($output->total)->toBe(4500);
        expect($output->products)->toHaveCount(1);
        expect($output->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
        expect($output->products[0]->productId)->toBe($product1->getId()->getValue());
        expect($output->products[0]->name)->toBe($product1->name);
        expect($output->products[0]->price)->toBe(1500);
        expect($output->products[0]->amount)->toBe(3);
        expect($output->products[0]->total)->toBe(4500);
    });
});
