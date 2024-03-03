<?php

namespace Core\Application\Usecases\Sale;

class SaleProductUsecaseOutput
{
    public function __construct(
        public readonly string $productId,
        public readonly string $name,
        public readonly int $price,
        public readonly int $amount,
        public readonly int $total,
    ) {
    }
}
