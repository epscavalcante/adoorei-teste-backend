<?php

use Core\Application\Usecases\Sale\CancelSaleUsecase;
use Core\Application\Usecases\Sale\CancelSaleUsecaseInput;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;
use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\SaleStatusEnum;
use Core\Domain\ValueObjects\Uuid;
use Core\Infra\Repositories\ProductMemoryRepository;
use Core\Infra\Repositories\SaleMemoryRepository;

describe('CancelSaleUsecaseUnitTest', function () {
    test('Should throws SaleNotFoundException', function () {
        $saleRepository = new SaleMemoryRepository();
        $productRepository = new ProductMemoryRepository();
        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $input = new CancelSaleUsecaseInput((Uuid::create())->getValue());
        $usecase->execute($input);
    })->throws(SaleNotFoundException::class);

    test('Should throws SaleAlredBeCanceledException', function () {
        $saleRepository = new SaleMemoryRepository();
        $productRepository = new ProductMemoryRepository();
        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $sale = Sale::create();
        $sale->markAsCancelled();
        $saleRepository->insert($sale);
        $input = new CancelSaleUsecaseInput($sale->getId()->getValue());
        $usecase->execute($input);
    })->throws(SaleAlreadBeCancelledException::class);

    test('Should cancel sale', function () {
        $saleRepository = new SaleMemoryRepository();
        $productRepository = new ProductMemoryRepository();
        $usecase = new CancelSaleUsecase(
            saleRepository: $saleRepository,
            productRepository: $productRepository
        );
        $sale = Sale::create();
        $saleRepository->insert($sale);
        $input = new CancelSaleUsecaseInput($sale->getId()->getValue());
        $output = $usecase->execute($input);

        expect($output)->toBeInstanceOf(SaleUsecaseOutput::class);
        expect($output->saleId)->toBe($sale->getId()->getValue());
        expect($output->status)->toBe(SaleStatusEnum::CANCELLED->value);
    });
});
