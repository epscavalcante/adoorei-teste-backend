<?php

namespace Core\Application\Usecases\Sale;

use Core\Application\Validations\IProductIdsExistsValidation;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\ValueObjects\Uuid;

class UpdateSaleUsecase
{
    public function __construct(
        private ISaleRepository $saleRepository,
        private IProductRepository $productRepository,
        private IProductIdsExistsValidation $productIdsExistsValidation
    ) {
    }

    public function execute(UpdateSaleUsecaseInput $input): SaleUsecaseOutput
    {
        $sale = $this->saleRepository->find(new Uuid($input->saleId));

        if ($sale->isCancelled())
            throw new SaleAlreadBeCancelledException($sale->getId());

        $productIds = array_map(fn ($product) => $product['productId'], $input->products);
        $result = $this->productIdsExistsValidation->validate($productIds);

        if (count($result['notExists']))
            throw new EntityValidationException(
                error: array_map(fn ($error) => $error->getMessage(), $result['notExists']),
                field: 'productId'
            );

        $saleProducts = array_map(fn ($product) => SaleProduct::create(
            productId: $product['productId'],
            // name: $product['name'],
            amount: $product['amount'],
            price: $product['price']
        ), $input->products);

        $sale->syncProducts($saleProducts);

        $this->saleRepository->update($sale);

        $saleProductsId = array_map(fn (SaleProduct $saleProduct) => $saleProduct->productId, $sale->getProducts());
        $productsRelated = $this->productRepository->findByIds($saleProductsId);

        return SaleUsecaseOutput::create($sale, $productsRelated);
    }
}
