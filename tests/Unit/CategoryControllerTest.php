<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    // Seed database
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // Test to retrieve all categories
    public function testCanRetrieveAllCategories()
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name']
        ]);
    }

    // Test to create a new category
    public function testCanCreateCategory()
    {
        $response = $this->post('/api/categories', [
            'name' => 'New Category'
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'New Category'
        ]);
    }

    // Test to retrieve a single category
    public function testCanRetrieveSingleCategory()
    {
        $category = Category::factory()->create();
        $response = $this->get('/api/categories/' . $category->id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

    // Test to update a category
    public function testCanUpdateCategory()
    {
        $category = Category::factory()->create();
        $response = $this->put('/api/categories/' . $category->id, [
            'name' => 'Updated Category'
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Updated Category'
        ]);
    }

    // Test to delete a category
    public function testCanDeleteCategory()
    {
        $category = Category::factory()->create();
        $response = $this->delete('/api/categories/' . $category->id);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Category deleted successfully'
        ]);
    }

    // Test validation errors
    public function testValidationErrors()
    {
        $response = $this->post('/api/categories', [
            'name' => ''
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
}
