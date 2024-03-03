<?php

namespace Core\Application\Usecases\Sale;

use Core\Domain\Entities\SaleProduct;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\ValueObjects\Uuid;

class FindSaleUsecase
{
    public function __construct(
        private readonly ISaleRepository $saleRepository,
        private readonly IProductRepository $productRepository
    ) {
    }

    public function execute(FindSaleUsecaseInput $input): SaleUsecaseOutput
    {
        $sale = $this->saleRepository->find(
            saleId: new Uuid($input->saleId)
        );

        $saleProductsId = array_map(fn (SaleProduct $saleProduct) => $saleProduct->productId, $sale->getProducts());
        $productsRelated = $this->productRepository->findByIds($saleProductsId);

        return SaleUsecaseOutput::create($sale, $productsRelated);
    }
}
