<?php

namespace Core\Domain\Exceptions;

use Exception;

class PriceNegativeValueException extends Exception
{
    public function __construct($value)
    {
        $message = "The value ({$value}) must be grather than zero.";
        parent::__construct($message);
    }
}
