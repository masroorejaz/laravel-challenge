<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductToCategory;

class ProductToCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    // Seed database
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // Test to create a new product-to-category relationship
    public function testCanCreateProductToCategory()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        
        $response = $this->post('/api/product-to-category', [
            'product_id' => $product->id,
            'category_id' => $category->id
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'product_id' => $product->id,
            'category_id' => $category->id
        ]);
    }

    // Test to retrieve a single product-to-category relationship
    public function testCanRetrieveSingleProductToCategory()
    {
        $productToCategory = ProductToCategory::factory()->create();
        $response = $this->get('/api/product-to-category/' . $productToCategory->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $productToCategory->id,
            'product_id' => $productToCategory->product_id,
            'category_id' => $productToCategory->category_id,
        ]);
    }

    // Test to update an existing product-to-category relationship
    public function testCanUpdateProductToCategory()
    {
        $productToCategory = ProductToCategory::factory()->create();
        $newProduct = Product::factory()->create();
        $newCategory = Category::factory()->create();
        
        $response = $this->put('/api/product-to-category/' . $productToCategory->id, [
            'product_id' => $newProduct->id,
            'category_id' => $newCategory->id
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'product_id' => $newProduct->id,
            'category_id' => $newCategory->id
        ]);
    }

    // Test to delete a product-to-category relationship
    public function testCanDeleteProductToCategory()
    {
        $productToCategory = ProductToCategory::factory()->create();
        $response = $this->delete('/api/product-to-category/' . $productToCategory->id);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Product to Category relationship deleted successfully'
        ]);
    }

     // Test validation errors (continued)
     public function testValidationErrors()
     {
         $response = $this->post('/api/product-to-category', [
             'product_id' => 999, // Invalid product ID
             'category_id' => 999  // Invalid category ID
         ]);
         $response->assertStatus(422); // Unprocessable Entity
         $response->assertJsonValidationErrors(['product_id', 'category_id']);
     }

 }
