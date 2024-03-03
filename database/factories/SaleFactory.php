<?php

namespace Database\Factories;

use Core\Domain\SaleStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->boolean() ? SaleStatusEnum::CANCELLED->value : SaleStatusEnum::OPENED->value,
            'total' =>  $this->faker->numberBetween(20, 3000)
        ];
    }
}
