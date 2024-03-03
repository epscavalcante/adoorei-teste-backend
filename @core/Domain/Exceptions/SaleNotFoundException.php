<?php

namespace Core\Domain\Exceptions;

use Core\Domain\Entities\Sale;
use Core\Domain\ValueObjects\Uuid;

class SaleNotFoundException extends EntityNotFoundException
{
    public function __construct(Uuid $saleId)
    {
        parent::__construct(Sale::class, $saleId);
    }
}
