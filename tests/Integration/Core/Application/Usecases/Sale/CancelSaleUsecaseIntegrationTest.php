<?php

use App\Models\Sale as SaleModel;
use App\Models\Product as ProductModel;
use App\Repositories\Eloquent\ProductEloquentRepository;
use App\Repositories\Eloquent\SaleEloquentRepository;
use Core\Application\Usecases\Sale\CancelSaleUsecase;
use Core\Application\Usecases\Sale\CancelSaleUsecaseInput;
use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\SaleStatusEnum;
use Core\Domain\ValueObjects\Uuid;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;

describe('CancelSaleUsecaseIntegrationTest', function () {
    test('Should throws SaleNotFoundException', function () {
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository,
        );
        $input = new CancelSaleUsecaseInput((Uuid::create())->getValue());
        $usecase->execute($input);
    })->throws(SaleNotFoundException::class);

    test('Should throws SaleAlredBeCanceledException', function () {
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $sale = Sale::create();
        $sale->markAsCancelled();
        $saleRepository->insert($sale);
        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new CancelSaleUsecaseInput($sale->getId()->getValue());
        $usecase->execute($input);
    })->throws(SaleAlreadBeCancelledException::class);

    test('Should cancel sale', function () {
        $productRepository = new ProductEloquentRepository(new ProductModel());
        $saleRepository = new SaleEloquentRepository(new SaleModel());
        $sale = Sale::create();
        $saleRepository->insert($sale);

        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new CancelSaleUsecaseInput($sale->getId()->getValue());
        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->toBe($sale->getId()->getValue());
        expect($output->status)->toBe(SaleStatusEnum::CANCELLED->value);
    });
});
