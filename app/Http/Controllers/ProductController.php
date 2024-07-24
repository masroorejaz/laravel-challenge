<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    // Retrieve paginated products based on page number
    public function index($page = 1)
    {
        // Define the number of items per page
        $perPage = 10; // Adjust this value as needed

        // Ensure the page number is a valid integer and greater than 0
        $page = max(1, (int) $page);

        // Retrieve paginated products
        $products = Product::paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }


    // Create a new product
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
            ]);

            $product = Product::create($validatedData);

            return response()->json($product, 201); // Status code 201 for Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Retrieve a single product by ID
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); // Status code 404 for Not Found
        }

        return response()->json($product);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
            ]);

            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404); // Status code 404 for Not Found
            }

            $product->update($validatedData);

            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); // Status code 404 for Not Found
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200); // Status code 200 for OK
    }
}
