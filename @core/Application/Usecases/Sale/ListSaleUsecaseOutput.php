<?php

namespace Core\Application\Usecases\Sale;

class ListSaleUsecaseOutput
{
    public function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }
}
