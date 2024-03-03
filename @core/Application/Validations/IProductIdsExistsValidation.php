<?php

namespace Core\Application\Validations;

interface IProductIdsExistsValidation
{
    public function validate(array $ids): array;
}
