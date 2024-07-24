<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductToCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductToCategoryController extends Controller
{
    // Create a new product-to-category relationship
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
            ]);

            $productToCategory = ProductToCategory::create($validatedData);

            return response()->json($productToCategory, 201); // Status code 201 for Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Retrieve a single product-to-category relationship by ID
    public function show($id)
    {
        $productToCategory = ProductToCategory::find($id);

        if (!$productToCategory) {
            return response()->json(['message' => 'Product to Category relationship not found'], 404); // Status code 404 for Not Found
        }

        return response()->json($productToCategory);
    }

    // Update an existing product-to-category relationship
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
            ]);

            $productToCategory = ProductToCategory::find($id);

            if (!$productToCategory) {
                return response()->json(['message' => 'Product to Category relationship not found'], 404); // Status code 404 for Not Found
            }

            $productToCategory->update($validatedData);

            return response()->json($productToCategory);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Delete a product-to-category relationship
    public function destroy($id)
    {
        $productToCategory = ProductToCategory::find($id);

        if (!$productToCategory) {
            return response()->json(['message' => 'Product to Category relationship not found'], 404); // Status code 404 for Not Found
        }

        $productToCategory->delete();

        return response()->json(['message' => 'Product to Category relationship deleted successfully'], 200); // Status code 200 for OK
    }

    // Retrieve all products with their categories
    public function productsWithCategories()
    {
        $products = Product::with('categories')->get();

        $productsWithCategories = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'categories' => $product->categories->pluck('name')
            ];
        });

        return response()->json($productsWithCategories);
    }

    // Retrieve products filtered by category
    public function getProductsByCategory($category_id)
    {
        // Validate the category ID
        if (!Category::find($category_id)) {
            return response()->json(['message' => 'Category not found'], 404); // Status code 404 for Not Found
        }

        // Retrieve products associated with the provided category ID
        $products = Product::whereHas('categories', function($query) use ($category_id) {
            $query->where('category_id', $category_id);
        })->with('categories')->get();

        $productsWithCategories = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'categories' => $product->categories->pluck('name')
            ];
        });

        return response()->json($productsWithCategories);
    }
}
