<?php

namespace Core\Domain\Exceptions;

use Core\Domain\ValueObjects\Uuid;
use Exception;

class SaleCantBeCancelledException extends Exception
{
    public function __construct(Uuid $saleId)
    {
        $message = "The sale ({$saleId->getValue()}) can't be cancelled.";
        parent::__construct($message);
    }
}
