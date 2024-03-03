<?php

namespace Core\Application\Usecases\Sale;

class FindSaleUsecaseInput
{
    public function __construct(
        public readonly string $saleId,
    ) {
    }
}
