<?php

namespace Core\Application\Usecases\Sale;

class UpdateSaleUsecaseInput
{
    public function __construct(
        public readonly string $saleId,
        public readonly array $products,
    ) {
    }
}
