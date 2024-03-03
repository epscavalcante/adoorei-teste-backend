<?php

namespace Core\Application\Usecases\Sale;

class CancelSaleUsecaseInput
{
    public function __construct(
        public readonly string $saleId,
    ) {
    }
}
