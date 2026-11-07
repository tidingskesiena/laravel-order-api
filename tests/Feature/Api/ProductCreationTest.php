<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_product(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Lenovo ThinkPad T14',
            'sku' => 'LENOVO-T14-TEST-001',
            'description' => 'Business laptop',
            'price' => 380000,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Product created successfully.')
            ->assertJsonPath('data.sku', 'LENOVO-T14-TEST-001');

        $this->assertDatabaseHas('products', [
            'sku' => 'LENOVO-T14-TEST-001',
            'stock_quantity' => 5,
        ]);
    }

    public function test_it_rejects_duplicate_sku(): void
    {
        $product = [
            'name' => 'Dell Latitude',
            'sku' => 'DUPLICATE-SKU-001',
            'price' => 250000,
            'stock_quantity' => 4,
        ];

        $this->postJson('/api/products', $product)
            ->assertStatus(201);

        $this->postJson('/api/products', $product)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['sku']);

        $this->assertDatabaseCount('products', 1);
    }

    public function test_it_rejects_negative_price(): void
    {
        $this->postJson('/api/products', [
            'name' => 'Invalid Product',
            'sku' => 'INVALID-PRICE-001',
            'price' => -500,
            'stock_quantity' => 5,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['price']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_it_rejects_negative_stock(): void
    {
        $this->postJson('/api/products', [
            'name' => 'Invalid Stock Product',
            'sku' => 'INVALID-STOCK-001',
            'price' => 15000,
            'stock_quantity' => -3,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['stock_quantity']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_it_rejects_missing_required_fields(): void
    {
        $this->postJson('/api/products', [
            'description' => 'Missing required fields',
        ])->assertStatus(422)
          ->assertJsonValidationErrors([
              'name',
              'sku',
              'price',
              'stock_quantity',
          ]);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_it_rejects_price_with_more_than_two_decimal_places(): void
    {
        $this->postJson('/api/products', [
            'name' => 'Precision Test',
            'sku' => 'PRECISION-TEST-001',
            'price' => 125000.999,
            'stock_quantity' => 2,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['price']);

        $this->assertDatabaseCount('products', 0);
    }
}