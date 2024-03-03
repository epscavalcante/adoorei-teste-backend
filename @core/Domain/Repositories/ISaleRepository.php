<?php

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Sale;
use Core\Domain\ValueObjects\Uuid;

interface ISaleRepository
{
    /**
     * @return Sale[]
     */
    public function list(): array;
    public function find(Uuid $saleId): Sale;
    public function insert(Sale $sale): void;
    public function update(Sale $sale): void;
}
