<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(10)->create();

        Product::factory()->create([
            'name' => 'iPhone 15 Pro',
            'description' => 'Smartphone Apple iPhone 15 Pro 128GB',
            'price' => 7999.99,
            'stock' => 25,
            'sku' => 'IPH-15-PRO-128',
            'active' => true,
        ]);

        Product::factory()->create([
            'name' => 'MacBook Air M3',
            'description' => 'MacBook Air com chip M3, 8GB RAM, 256GB SSD',
            'price' => 12999.99,
            'stock' => 10,
            'sku' => 'MBA-M3-8-256',
            'active' => true,
        ]);
    }
}