<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    // Retrieve all categories
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Create a new category
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::create($validatedData);

            return response()->json($category, 201); // Status code 201 for Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Retrieve a single category by ID
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404); // Status code 404 for Not Found
        }

        return response()->json($category);
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = Category::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404); // Status code 404 for Not Found
            }

            $category->update($validatedData);

            return response()->json($category);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Status code 422 for Unprocessable Entity
        }
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404); // Status code 404 for Not Found
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200); // Status code 200 for OK
    }
}
