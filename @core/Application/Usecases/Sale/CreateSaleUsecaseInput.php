<?php

namespace Core\Application\Usecases\Sale;

class CreateSaleUsecaseInput
{
    public function __construct(
        public readonly array $products,
    ) {
    }
}
