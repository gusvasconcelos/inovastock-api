<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $url = '/api/v1/products';

    public function test_index_with_pagination(): void
    {
        $user = User::factory()->create();

        ProductFactory::new()->count(5)->create();

        $response = $this->actingAsUser($user)->getJson($this->url);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'sku',
                        'active',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'current_page',
                'per_page',
                'total',
            ]);
    }

    public function test_all_products_without_pagination(): void
    {
        $user = User::factory()->create();

        ProductFactory::new()->count(5)->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/all");

        $response
            ->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_store_product_successfully(): void
    {
        $user = User::factory()->create();

        $product = ProductFactory::new()->make()->setAppends([])->toArray();

        $response = $this->actingAsUser($user)->postJson($this->url, $product);

        $response
            ->assertStatus(201)
            ->assertJson($product);

        $this->assertDatabaseHas('products', $product);
    }

    public function test_store_product_with_validation_errors(): void
    {
        $user = User::factory()->create();

        $productData = [
            'name' => '',
            'price' => -10,
            'stock' => -5,
        ];

        $response = $this->actingAsUser($user)->postJson($this->url, $productData);

        $response->assertStatus(422);
    }

    public function test_show_product_successfully(): void
    {
        $user = User::factory()->create();

        $product = ProductFactory::new()->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/{$product->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
            ]);
    }

    public function test_show_nonexistent_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/999");

        $response->assertStatus(404);
    }

    public function test_update_product_successfully(): void
    {
        $user = User::factory()->create();

        $product = ProductFactory::new()->create();

        $productUpdated = ProductFactory::new()->make()->setAppends([])->toArray();

        $response = $this->actingAsUser($user)->putJson("$this->url/{$product->id}", $productUpdated);

        $response
            ->assertStatus(200)
            ->assertJson($productUpdated);

        $this->assertDatabaseHas('products', $productUpdated);
    }

    public function test_update_product_with_validation_errors(): void
    {
        $user = User::factory()->create();

        $product = ProductFactory::new()->create();

        $updateData = [
            'price' => -50,
            'stock' => -10,
        ];

        $response = $this->actingAsUser($user)->putJson("$this->url/{$product->id}", $updateData);

        $response->assertStatus(422);
    }

    public function test_delete_product_successfully(): void
    {
        $user = User::factory()->create();

        $product = ProductFactory::new()->create();

        $response = $this->actingAsUser($user)->deleteJson("$this->url/{$product->id}");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Produto deletado com sucesso.']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_nonexistent_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->deleteJson("$this->url/999");

        $response->assertStatus(404);
    }

    public function test_unauthenticated_access_denied(): void
    {
        $response = $this->getJson($this->url);

        $response->assertStatus(401);
    }

    public function test_create_product_with_duplicate_sku(): void
    {
        $product = ProductFactory::new()->create(['sku' => 'UNIQUE-SKU']);

        $productData = [
            'name' => 'Produto Duplicado',
            'description' => 'Teste de SKU duplicado',
            'price' => 99.99,
            'stock' => 10,
            'sku' => 'UNIQUE-SKU',
            'active' => true,
        ];

        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->postJson($this->url, $productData);

        $response->assertStatus(422);
    }

    public function test_update_product_with_duplicate_sku(): void
    {
        ProductFactory::new()->create(['sku' => 'SKU-001']);
        $product2 = ProductFactory::new()->create(['sku' => 'SKU-002']);

        $updateData = ['sku' => 'SKU-001'];

        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->putJson("$this->url/{$product2->id}", $updateData);

        $response->assertStatus(422);
    }
}