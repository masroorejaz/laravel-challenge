<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // Seed database
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Seed database
    }

    // Test to retrieve all products
    public function testCanRetrieveAllProducts()
    {
        $response = $this->get('/api/products/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'stock']
        ]);
    }

    // Test to create a new product
    public function testCanCreateProduct()
    {
        $response = $this->post('/api/products', [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 19.99,
            'stock' => 10
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'New Product',
            'price' => 19.99,
            'stock' => 10
        ]);
    }

    // Test to retrieve a single product
    public function testCanRetrieveSingleProduct()
    {
        $product = Product::factory()->create();
        $response = $this->get('/api/products/' . $product->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }

    // Test to update a product
    public function testCanUpdateProduct()
    {
        $product = Product::factory()->create();
        $response = $this->put('/api/products/' . $product->id, [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 29.99,
            'stock' => 15
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Updated Product',
            'price' => 29.99,
            'stock' => 15
        ]);
    }

    // Test to delete a product
    public function testCanDeleteProduct()
    {
        $product = Product::factory()->create();
        $response = $this->delete('/api/products/' . $product->id);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product deleted successfully'
        ]);
    }

    // Test validation errors
    public function testValidationErrors()
    {
        $response = $this->post('/api/products', [
            'name' => '',
            'price' => 'invalid',
            'stock' => 'invalid'
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price', 'stock']);
    }
}
