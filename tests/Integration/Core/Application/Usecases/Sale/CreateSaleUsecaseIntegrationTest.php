<?php

use App\Models\Product as ProductModel;
use App\Models\Sale as SaleModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use App\Repositories\Eloquent\SaleEloquentRepository;
use Core\Application\Usecases\Sale\CreateSaleUsecase;
use Core\Application\Usecases\Sale\CreateSaleUsecaseInput;
use Core\Application\Usecases\Sale\SaleProductUsecaseOutput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;
use Core\Application\Validations\ProductIdsExistsValidation;
use Core\Domain\Entities\Product;
use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\ValueObjects\Uuid;

describe('CreateSaleUsecase Ingration Test', function () {

    test('Should throws EntityValidationException because products is Empty', function () {
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new CreateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $saleRepository->insert($sale);
        $input = new CreateSaleUsecaseInput(
            products: []
        );

        $usecase->execute($input);
    })->throws(EntityValidationException::class);

    test('Should throws EntityValidationException because products not exist', function () {
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new CreateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $sale = Sale::create();
        $saleRepository->insert($sale);
        $input = new CreateSaleUsecaseInput(
            products: [
                [
                    'productId' => Uuid::create()->getValue(),
                    'name' => 'Product 1',
                    'price' => 1500,
                    'amount' => 2,
                ],
            ]
        );

        $usecase->execute($input);
    })->throws(EntityValidationException::class);

    test('Should creates an sale', function () {
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $productIdsExistsValidation = new ProductIdsExistsValidation($productRepository);
        $usecase = new CreateSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
            productIdsExistsValidation: $productIdsExistsValidation
        );
        $product = Product::create(
            name: 'Product 1',
            description: 'Product 1',
            price: 1500,
        );
        $productRepository->insert($product);
        $sale = Sale::create();
        $saleRepository->insert($sale);

        $input = new CreateSaleUsecaseInput(
            products: [
                [
                    'productId' => $product->getId()->getValue(),
                    'name' => $product->name,
                    'price' => $product->getPrice(),
                    'amount' => 2,
                ],
            ]
        );

        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->not->toBeNull();
        expect($output->total)->toBe(3000);
        expect($output->products)->toHaveCount(1);
        expect($output->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
        expect($output->products[0]->productId)->toBe($product->getId()->getValue());
        expect($output->products[0]->name)->toBe($product->name);
        expect($output->products[0]->price)->toBe(1500);
        expect($output->products[0]->amount)->toBe(2);
        expect($output->products[0]->total)->toBe(3000);
    });
});
