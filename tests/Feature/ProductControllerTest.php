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
     */
    public function test_index_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    /**
     * @test
     * test endpoint index returns paginated products for authenticated user
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
     * test endpoint store creates product
     */
    public function test_store_creates_product(): void
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
}
