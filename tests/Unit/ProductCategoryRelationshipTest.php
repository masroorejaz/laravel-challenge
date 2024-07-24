<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductToCategory;

class ProductCategoryRelationshipTest extends TestCase
{
    use RefreshDatabase;

    // Seed database
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // Test products with their categories
    public function testCanRetrieveProductsWithCategories()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        
        ProductToCategory::create([
            'product_id' => $product->id,
            'category_id' => $category->id
        ]);

        $response = $this->get('/api/products_with_categories');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => $product->name,
            'categories' => [$category->name]
        ]);
    }

    // Test products filtered by category
    public function testCanRetrieveProductsByCategory()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        
        ProductToCategory::create([
            'product_id' => $product->id,
            'category_id' => $category->id
        ]);

        $response = $this->get('/api/products_by_category/' . $category->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => $product->name,
            'categories' => [$category->name]
        ]);
    }

    // Test not found category
    public function testCategoryNotFound()
    {
        $response = $this->get('/api/products_by_category/999');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Category not found'
        ]);
    }
}
