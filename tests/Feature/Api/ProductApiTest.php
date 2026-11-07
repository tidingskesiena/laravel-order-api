<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the API returns a list of products.
     */
    public function test_it_can_list_products(): void
    {
        Product::create([
            'name' => 'HP EliteBook 840 G8',
            'sku' => 'HP-840-G8-001',
            'description' => 'Business laptop',
            'price' => '450000.00',
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Products retrieved successfully.'
            )
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'HP-840-G8-001');
    }

    /**
     * Test that the API retrieves one product.
     */
    public function test_it_can_show_a_single_product(): void
    {
        $product = Product::create([
            'name' => 'HP EliteBook 840 G8',
            'sku' => 'HP-840-G8-001',
            'description' => 'Business laptop',
            'price' => '450000.00',
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->getJson(
            '/api/products/' . $product->id
        );

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Product retrieved successfully.'
            )
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.sku', 'HP-840-G8-001');
    }

    /**
     * Test that nonexistent products return HTTP 404.
     */
    public function test_it_returns_404_for_missing_products(): void
    {
        $response = $this->getJson('/api/products/9999');

        $response->assertStatus(404);
    }
}