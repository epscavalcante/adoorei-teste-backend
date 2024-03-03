<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleProduct>
 */
class SaleProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sale = Sale::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();
        $amount = $this->faker->numberBetween(1, 6);
        $total = $product->price * $amount;

        return [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'amount' => $amount,
            'total' => $total,
        ];
    }
}
