<?php

namespace Core\Application\Usecases\Sale;

use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\ValueObjects\Uuid;

class CancelSaleUsecase
{
    public function __construct(private readonly ISaleRepository $saleRepository, private readonly IProductRepository $productRepository)
    {
    }

    public function execute(CancelSaleUsecaseInput $input): SaleUsecaseOutput
    {
        $sale = $this->saleRepository->find(new Uuid($input->saleId));

        if ($sale->isCancelled()) {
            throw new SaleAlreadBeCancelledException($sale->getId());
        }

        $sale->markAsCancelled();

        $this->saleRepository->update($sale);

        $saleProductsId = array_map(fn (SaleProduct $saleProduct) => $saleProduct->productId, $sale->getProducts());
        $productsRelated = $this->productRepository->findByIds($saleProductsId);

        return SaleUsecaseOutput::create($sale, $productsRelated);
    }
}
