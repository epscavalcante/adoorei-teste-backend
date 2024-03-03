<?php

namespace Core\Application\Usecases\Sale;

use Core\Application\Validations\IProductIdsExistsValidation;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\Repositories\IProductRepository;
use Core\Application\Usecases\Sale\SaleUsecaseOutput;

class CreateSaleUsecase
{
    public function __construct(
        private ISaleRepository $saleRepository,
        private IProductRepository $productRepository,
        private IProductIdsExistsValidation $productIdsExistsValidation
    ) {
    }

    public function execute(CreateSaleUsecaseInput $input): SaleUsecaseOutput
    {
        if (count($input->products) === 0) {
            throw new EntityValidationException(
                error: 'The products field must be required and have at last one item',
                field: 'productId'
            );
        }

        $productIds = array_map(fn ($product) => $product['productId'], $input->products);
        $result = $this->productIdsExistsValidation->validate($productIds);

        if (count($result['notExists'])) {
            throw new EntityValidationException(
                error: array_map(fn ($error) => $error->getMessage(), $result['notExists']),
                field: 'productId'
            );
        }

        $sale = Sale::create();
        $saleProducts = array_map(fn ($product) => SaleProduct::create(
            productId: $product['productId'],
            amount: $product['amount'],
            price: $product['price']
        ), $input->products);

        $sale->syncProducts($saleProducts);

        $this->saleRepository->insert($sale);

        $saleProductsId = array_map(fn (SaleProduct $saleProduct) => $saleProduct->productId, $sale->getProducts());
        $productsRelated = $this->productRepository->findByIds($saleProductsId);

        return SaleUsecaseOutput::create($sale, $productsRelated);
    }
}
