<?php

namespace Core\Domain;

use Core\Domain\Entities\Sale;
use Exception;

class SaleStatusFactory
{
    public static function create(SaleStatusEnum $status, Sale $sale)
    {
        if ($status->value === SaleStatusEnum::OPENED->value)
            return new OpenedSaleStatus($sale);
        if ($status->value === SaleStatusEnum::CANCELLED->value)
            return new CancelledSaleStatus($sale);

        throw new Exception('Invalid status');
    }
}
