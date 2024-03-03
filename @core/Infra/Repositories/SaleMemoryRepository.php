<?php

namespace Core\Infra\Repositories;

use Core\Domain\Entities\Sale;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\ValueObjects\Uuid;

class SaleMemoryRepository implements ISaleRepository
{
    /**
     * @var Array<Sale> $items
     */
    private array $items = [];

    public function insert(Sale $sale): void
    {
        array_push($this->items, $sale);
    }

    public function list(): array
    {
        return $this->items;
    }

    public function find(Uuid $saleId): Sale
    {
        $saleFound = null;

        foreach ($this->items as $sale) {
            if ($sale->getId()->equals($saleId))
                $saleFound = $sale;
        }

        return $saleFound ?? throw new SaleNotFoundException($saleId);
    }

    public function update(Sale $sale): void
    {
        foreach ($this->items as $key => $sale) {
            if ($sale->getId()->equals($sale->getId())) {
                unset($this->items[$key]);
                $this->items[$key] = $sale;
                return;
            }
        }

        throw new SaleNotFoundException($sale->getId());
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
