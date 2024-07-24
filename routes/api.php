<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductToCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
Route::get('products/{per_page?}', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::post('product_to_category', [ProductToCategoryController::class, 'store']);
Route::get('product_to_category/{id}', [ProductToCategoryController::class, 'show']);
Route::put('product_to_category/{id}', [ProductToCategoryController::class, 'update']);
Route::delete('product_to_category/{id}', [ProductToCategoryController::class, 'destroy']);
Route::get('products_with_categories', [ProductToCategoryController::class, 'productsWithCategories']);
Route::get('products_by_category/{category_id}', [ProductToCategoryController::class, 'getProductsByCategory']);
