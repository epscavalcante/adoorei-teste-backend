<?php

namespace Core\Domain\Repositories;

use Core\Domain\Entities\Product;

interface IProductRepository
{
    /**
     * @return Product[]
     */
    public function list(): array;

    public function insert(Product $produt): Product;

    /**
     * @param  array<Uuid>  $productIds
     */
    public function existsByIds(array $productIds): array;

    /**
     * @param  array<Uuid>  $productIds
     */
    public function findByIds(array $productIds): array;
}
