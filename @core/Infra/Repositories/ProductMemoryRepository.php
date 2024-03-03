<?php

namespace Core\Infra\Repositories;

use Core\Domain\Entities\Product;
use Core\Domain\Repositories\IProductRepository;

class ProductMemoryRepository implements IProductRepository
{
    /**
     * @var Array<Product> $items
     */
    private array $items = [];

    public function insert(Product $product): Product
    {
        array_push($this->items, $product);

        return $product;
    }

    public function list(): array
    {
        return $this->items;
    }

    /**
     * @param Array<ProductId>
     */
    public function existsByIds(array $productIds): array
    {
        if (count($productIds) === 0) {
            return [
                'exists' => [],
                'notExists' => []
            ];
        }

        $exists = [];
        $notExists = [];

        foreach ($productIds as $productId) {
            $found = null;
            foreach ($this->items as $item) {
                if ($item->getId()->equals($productId))
                    $found = $item;
            }

            if ($found) array_push($exists, $found);
            else array_push($notExists, $productId);
        }

        return [
            'exists' => $exists,
            'notExists' => $notExists,
        ];
    }

    /**
     * @param Array<Uuid> $productIds
     */
    public function findByIds(array $productIds): array
    {
        if(count($productIds) === 0) return [];

        $productsFound = [];

        foreach ($this->items as $product) {
            foreach($productIds as $productId) {
                if ($product->getId()->equals($productId))
                    array_push($productsFound, $product);
            }
        }

        return $productsFound;
    }

    /**
     * @return array<Product>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
