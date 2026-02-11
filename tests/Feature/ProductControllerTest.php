<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * test endpoint index returns 401 when unauthenticated
     * should return 401
     */
    public function test_index_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    /**
     * @test
     * test endpoint index returns paginated products for authenticated user
     * should return 200 and paginated products
     */
    public function test_index_returns_paginated_products_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'per_page',
            'total',
        ]);
    }

    /**
     * @test
     * test endpoint store product
     * should return 201 and create product in database
     */
    public function test_store_product_succeeds(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $productData = [
            'name' => 'Paracetamol',
            'price' => 11.90,
            'type' => 'medication',
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201);
        $expectedInResponse = [
            'name' => $productData['name'],
            'type' => $productData['type'],
            'price' => number_format($productData['price'], 2, '.', ''),
        ];
        $response->assertJsonFragment($expectedInResponse);
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * @test
     * test endpoint update product
     * should return 200 and update product in database
     */
    public function test_update_product_succeeds(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::create([
            'name' => 'Old Product',
            'price' => 10.00,
            'type' => 'others',
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'price' => 15.50,
            'type' => 'medication',
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200);
        $expectedInResponse = [
            'name' => $updateData['name'],
            'type' => $updateData['type'],
            'price' => number_format($updateData['price'], 2, '.', ''),
        ];
        $response->assertJsonFragment($expectedInResponse);
        $this->assertDatabaseHas('products', array_merge(['id' => $product->id], $updateData));
    }
}
