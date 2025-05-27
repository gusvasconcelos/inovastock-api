<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'sku' => fake()->unique()->bothify('PRD-###-???'),
            'active' => fake()->boolean(80),
        ];
    }

    public function inactive(): self
    {
        return $this->state([
            'active' => false,
        ]);
    }

    public function outOfStock(): self
    {
        return $this->state([
            'stock' => 0,
        ]);
    }
}