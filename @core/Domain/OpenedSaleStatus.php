<?php

namespace Core\Domain;

use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleCantBeOpenedException;

class OpenedSaleStatus extends SaleStatus
{
    public function __construct(Sale $sale)
    {
        parent::__construct($sale);
        $this->value = SaleStatusEnum::OPENED;
    }

    public function open()
    {
        throw new SaleCantBeOpenedException($this->sale->getId());
    }

    public function cancel()
    {
        $this->sale->status = new CancelledSaleStatus($this->sale);
    }
}
