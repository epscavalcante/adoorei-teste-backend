<?php

namespace Core\Application\Usecases\Sale;

use Core\Domain\Entities\Product;
use Core\Domain\Entities\Sale;

class SaleUsecaseOutput
{
    private function __construct(
        public readonly string $saleId,
        public readonly int $total,
        public readonly string $status,
        public readonly array $products,
    ) {
    }

    /**
     * @param Array<Product> $productsRelated
     */
    public static function create(Sale $sale, array $productsRelated): self
    {
        $productsOutput = [];
        foreach ($productsRelated as $productRelated) {
            foreach ($sale->getProducts() as $saleProduct) {
                if ($productRelated->getId()->getValue() === $saleProduct->productId->getValue())
                    array_push(
                        $productsOutput,
                        new SaleProductUsecaseOutput(
                            productId: $saleProduct->productId->getValue(),
                            name: $productRelated->name,
                            price: $saleProduct->getPrice(),
                            amount: $saleProduct->getAmount(),
                            total: $saleProduct->getTotal()->getValue()
                        )
                    );
            }
        };

        return new self(
            saleId: $sale->getId()->getValue(),
            total: $sale->getTotal(),
            status: $sale->getStatus()->value,
            products: $productsOutput
        );
    }

}
