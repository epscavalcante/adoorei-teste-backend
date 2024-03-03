<?php

namespace Core\Domain\Exceptions;

use Exception;

class EntityValidationException extends Exception
{
    public function __construct(
        public array|string $error,
        public string $field
    ) {
        parent::__construct('The given data was invalid.');
        $this->error = $error;
        $this->field = $field;
    }

    public function getErrors()
    {
        return [
            "{$this->field}" => is_array($this->error) ? $this->error : [$this->error],
        ];
    }
}
