<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockCars>
 */
class CarsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cars_name' => fake()->name,
            'price' => fake()->numberBetween(0, 1000000000),
            'stock' => fake()->numberBetween(0, 100)
        ];
    }
}
