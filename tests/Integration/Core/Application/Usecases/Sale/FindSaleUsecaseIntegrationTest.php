<?php

use App\Models\Product as ProductModel;
use App\Models\Sale as SaleModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use App\Repositories\Eloquent\SaleEloquentRepository;
use Core\Application\Usecases\Sale\FindSaleUsecase;
use Core\Application\Usecases\Sale\FindSaleUsecaseInput;
use Core\Application\Usecases\Sale\SaleProductUsecaseOutput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;
use Core\Domain\Entities\Product;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\SaleStatusEnum;
use Core\Domain\ValueObjects\Uuid;

describe('FindSaleUsecaseIntegrationTest', function () {
    test('Should throws SaleNotFoundException', function () {
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $usecase = new FindSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new FindSaleUsecaseInput((Uuid::create())->getValue());
        $usecase->execute($input);
    })->throws(SaleNotFoundException::class);

    test('Should return a saled found', function () {
        $product = Product::create('test name product', 'some description', 100);
        $saleProduct = SaleProduct::create(
            productId: $product->getId()->getValue(),
            // name: $product->name,
            price: $product->getPrice(),
            amount: 1
        );
        $sale = Sale::create();
        $sale->addProduct($saleProduct);
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $productRepository->insert($product);
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
        expect($output->status)->toBe(SaleStatusEnum::OPENED->value);
        expect($output->products)->toHaveCount(1);
        expect($output->products[0])->toBeInstanceOf(SaleProductUsecaseOutput::class);
        expect($output->products[0]->productId)->toBe($product->getId()->getValue());
        expect($output->products[0]->name)->toBe($product->name);
        expect($output->products[0]->price)->toBe($product->getPrice());
        expect($output->products[0]->amount)->toBe(1);
        expect($output->products[0]->total)->toBe(100);
    });
});
