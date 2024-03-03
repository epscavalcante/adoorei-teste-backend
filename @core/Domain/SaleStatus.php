<?php

namespace Core\Domain;

use Core\Domain\Entities\Sale;

abstract class SaleStatus
{
    public SaleStatusEnum $value;

    public function __construct(
        protected Sale $sale
    ) {
    }

    abstract function open();
    abstract function cancel();
}
