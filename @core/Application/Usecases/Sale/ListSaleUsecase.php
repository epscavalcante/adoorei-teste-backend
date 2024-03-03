<?php

namespace Core\Application\Usecases\Sale;

use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\Repositories\ISaleRepository;

class ListSaleUsecase
{
    public function __construct(
        private readonly ISaleRepository $saleRepository,
        private readonly IProductRepository $productRepository,
    ) {
    }

    public function execute(): ListSaleUsecaseOutput
    {
        $sales = $this->saleRepository->list();

        $productsFromSales = $this->getProductsFromSales($sales);

        $saleProductsId = array_map(fn (SaleProduct $saleProduct) => $saleProduct->productId, $productsFromSales);
        $productsRelated = $this->productRepository->findByIds($saleProductsId);

        $salesOutput = [];
        foreach ($sales as $sale) {
            array_push($salesOutput, SaleUsecaseOutput::create($sale, $productsRelated));
        }

        return new ListSaleUsecaseOutput(
            items: $salesOutput,
            total: count($salesOutput)
        );
    }

    /**
     * @param  array<Sale>  $sales
     */
    private function getProductsFromSales(array $sales)
    {
        if (count($sales) === 0) {
            return [];
        }

        $products = [];

        foreach ($sales as $sale) {
            array_push($products, ...$sale->getProducts());
        }

        return $products;
    }
}
