<?php

namespace Core\Domain;

use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleCantBeCancelledException;
use Core\Domain\Exceptions\SaleCantBeOpenedException;

class CancelledSaleStatus extends SaleStatus
{
    public function __construct(Sale $sale)
    {
        parent::__construct($sale);
        $this->value = SaleStatusEnum::CANCELLED;
    }

    public function open()
    {
        throw new SaleCantBeOpenedException($this->sale->getId());
    }

    public function cancel()
    {
        throw new SaleCantBeCancelledException($this->sale->getId());
    }
}
