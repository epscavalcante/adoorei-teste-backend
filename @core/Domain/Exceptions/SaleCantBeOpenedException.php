<?php

namespace Core\Domain\Exceptions;

use Core\Domain\ValueObjects\Uuid;
use Exception;

class SaleCantBeOpenedException extends Exception
{
    public function __construct(Uuid $saleId)
    {
        $message = "The sale ({$saleId->getValue()}) can't be opened.";
        parent::__construct($message);
    }
}
