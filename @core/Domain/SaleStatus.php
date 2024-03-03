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

    abstract public function open();

    abstract public function cancel();
}
