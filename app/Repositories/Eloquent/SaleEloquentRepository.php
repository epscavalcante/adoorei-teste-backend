<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale as SaleModel;
use App\Models\SaleProduct as SaleProductModel;
use Core\Domain\Entities\Sale;
use Core\Domain\Entities\SaleProduct;
use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\Repositories\ISaleRepository;
use Core\Domain\ValueObjects\Uuid;
use Illuminate\Support\Facades\DB;

class SaleEloquentRepository implements ISaleRepository
{
    protected $model;

    public function __construct(SaleModel $model)
    {
        $this->model = $model;
    }

    public function insert(Sale $sale): void
    {
        $saleModelCreated = null;

        DB::beginTransaction();

        try {
            $saleModelCreated = $this->model->create([
                'id' => $sale->getId()->getValue(),
                'status' => $sale->getStatus()->value,
                'total' => $sale->getTotal(),
            ]);

            $this->syncProducts($saleModelCreated, $sale->getProducts());

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function list(): array
    {
        $salesModel = $this->model->with('productIds')->get();

        return array_map(
            callback: fn ($model) => $this->mapModelToEntity($model),
            array: $salesModel->all()
        );
    }

    public function find(Uuid $saleId): Sale
    {
        $saleFound = $this->_find($saleId);

        if (! $saleFound) {
            throw new SaleNotFoundException($saleId);
        }

        return $this->mapModelToEntity($saleFound);
    }

    public function update(Sale $sale): void
    {
        $saleModel = $this->_find($sale->getId());

        if (! $saleModel) {
            throw new SaleNotFoundException($sale->getId());
        }

        DB::beginTransaction();

        try {
            $saleModel->update([
                'total' => $sale->getTotal(),
                'status' => $sale->getStatus()->value,
            ]);

            $this->syncProducts($saleModel, $sale->getProducts());

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function mapModelToEntity(SaleModel $model): Sale
    {
        $products = array_map(
            callback: fn (SaleProductModel $model) => SaleProduct::create(
                productId: $model->product_id,
                price: $model->price,
                amount: $model->amount,
                // total: $model->total,
            ),
            array: $model->productIds->all()
        );

        return Sale::restore(
            saleId: $model->id,
            total: $model->total,
            products: $products,
            status: $model->status
        );
    }

    private function _find(Uuid $saleId)
    {
        return $this->model->with('productIds')->find($saleId->getValue());
    }

    /**
     * @param  array<SaleProduct>  $products
     */
    private function syncProducts(SaleModel $model, array $products)
    {
        $model->productIds()->delete();

        $productsToSync = array_map(
            callback: fn (SaleProduct $product) => [
                'product_id' => $product->productId->getValue(),
                'sale_id' => $model->id,
                'price' => $product->getPrice(),
                'amount' => $product->getAmount(),
                'total' => $product->getTotal()->getValue(),
            ],
            array: $products
        );

        $model->productIds()->createMany($productsToSync);
    }
}
