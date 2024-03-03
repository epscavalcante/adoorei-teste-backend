<?php

namespace App\Repositories\Eloquent;

use App\Models\Product as ProductModel;
use Core\Domain\Entities\Product;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\ValueObjects\Uuid;

class ProductEloquentRepository implements IProductRepository
{
    protected $model;

    public function __construct(ProductModel $model)
    {
        $this->model = $model;
    }

    public function insert(Product $product): Product
    {
        $productModelCreated = $this->model->create([
            'id' => $product->getId()->getValue(),
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->getPrice(),
        ]);

        return $this->mapModelToEntity($productModelCreated);
    }

    public function list(string $filter = '', $order = 'DESC'): array
    {
        $productsModel = $this->model->all();

        return $productsModel
            ->map(fn ($model) => $this->mapModelToEntity($model))
            ->toArray();
    }

    /**
     * @param  array<Uuid>  $productIds
     */
    public function existsByIds(array $productIds): array
    {
        if (count($productIds) === 0) {
            return [
                'exists' => [],
                'notExists' => [],
            ];
        }

        $exists = [];
        $notExists = [];
        $result = $this->model->whereIn('id', array_map(fn (Uuid $id) => $id->getValue(), $productIds))->get()->modelKeys();

        foreach ($productIds as $productId) {
            $exist = null;

            foreach ($result as $id) {
                if ($id === $productId->getValue()) {
                    $exist = $productId;
                }
            }

            if ($exist) {
                array_push($exists, $exist);
            } else {
                array_push($notExists, $productId);
            }
        }

        return [
            'exists' => $exists,
            'notExists' => $notExists,
        ];
    }

    /**
     * @param  array<Uuid>  $productIds
     */
    public function findByIds(array $productIds): array
    {
        if (count($productIds) === 0) {
            return [];
        }
        $result = $this->model->whereIn('id', array_map(fn (Uuid $id) => $id->getValue(), $productIds))->get();

        return array_map(fn (ProductModel $model) => $this->mapModelToEntity($model), $result->all());
    }

    private function mapModelToEntity(ProductModel $model): Product
    {
        return Product::create(
            productId: $model->id,
            name: $model->name,
            description: $model->description,
            price: $model->price
        );
    }
}
