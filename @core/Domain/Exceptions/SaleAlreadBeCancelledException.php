<?php

namespace Core\Domain\Exceptions;

use Core\Domain\ValueObjects\Uuid;
use Exception;

class SaleAlreadBeCancelledException extends Exception
{
    public function __construct(Uuid $saleId)
    {
        $message = "The sale ({$saleId->getValue()}) already be cancelled.";
        parent::__construct($message);
    }
}
