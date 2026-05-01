<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Kategori;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryApiController extends Controller
{
    /**
     * List all categories
     *
     * Get a paginated list of all categories with their associated products.
     * Requires API token authentication.
     *
     * @tags Categories
     * @authenticated
     * @response 200 {"message": "Category list retrieved successfully", "data": [{"id": 1, "product_id": 1, "name": "Electronics", "created_at": "2024-01-01T00:00:00Z"}]}
     * @response 500 {"message": "Error retrieving categories", "error": "error message"}
     */
    public function index()
    {
        try {
            $categories = Kategori::with('product')->get();
            
            return response()->json([
                'message' => 'Category list retrieved successfully',
                'data'    => $categories
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Category index error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error retrieving categories',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new category
     *
     * Create a new category for a product. Only the owner of the product can create categories for it.
     * Requires API token authentication.
     *
     * @tags Categories
     * @authenticated
     * @bodyParam product_id int required The ID of the product. Example: 1
     * @bodyParam name string required The name of the category. Example: Electronics
     * @response 201 {"message": "Kategori berhasil ditambahkan!", "data": {"id": 1, "product_id": 1, "name": "Electronics", "created_at": "2024-01-01T00:00:00Z"}}
     * @response 403 {"message": "Unauthorized: Product not found or does not belong to you"}
     * @response 422 {"message": "Validation error", "errors": {"product_id": ["The product_id field is required."]}}
     * @response 500 {"message": "Database error while creating category"}
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            // Verify that the product belongs to the authenticated user
            $product = Product::find($validated['product_id']);
            if (!$product || $product->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized: Product not found or does not belong to you',
                ], 403);
            }

            $category = Kategori::create($validated);
            $category->load('product');

            Log::info('Membuat data kategori', [
                'list' => $category
            ]);

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan!',
                'data'    => $category,
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Category store database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while creating category',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Category store unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category details
     *
     * Retrieve a specific category by its ID with its associated product information.
     * Requires API token authentication.
     *
     * @tags Categories
     * @authenticated
     * @urlParam id int required The ID of the category. Example: 1
     * @response 200 {"message": "Category retrieved successfully", "data": {"id": 1, "product_id": 1, "name": "Electronics", "created_at": "2024-01-01T00:00:00Z", "product": {"id": 1, "user_id": 1, "name": "Product Name"}}}
     * @response 404 {"message": "Category tidak ditemukan"}
     * @response 500 {"message": "Error retrieving category"}
     */
    public function show(int $id)
    {
        try {
            $category = Kategori::with('product')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Category retrieved successfully',
                'data'    => $category
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data kategori', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error retrieving category',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a category
     *
     * Update the name of a specific category. Only the owner of the associated product can update it.
     * Requires API token authentication.
     *
     * @tags Categories
     * @authenticated
     * @urlParam id int required The ID of the category. Example: 1
     * @bodyParam name string required The new name of the category. Example: Updated Category Name
     * @response 200 {"message": "Category updated successfully", "data": {"id": 1, "product_id": 1, "name": "Updated Category Name", "created_at": "2024-01-01T00:00:00Z"}}
     * @response 403 {"message": "Unauthorized: You can only update categories of your own products"}
     * @response 404 {"message": "Category tidak ditemukan"}
     * @response 422 {"message": "Validation error"}
     * @response 500 {"message": "Database error while updating category"}
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        try {
            $category = Kategori::with('product')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category tidak ditemukan',
                ], 404);
            }

            // Check if user owns the product that this category belongs to
            if ($category->product->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized: You can only update categories of your own products',
                ], 403);
            }

            $validated = $request->validated();
            $category->update($validated);
            $category->load('product');

            return response()->json([
                'message' => 'Category updated successfully',
                'data'    => $category
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Category update database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while updating category',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Category update unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a category
     *
     * Delete a specific category. Only the owner of the associated product can delete it.
     * Requires API token authentication.
     *
     * @tags Categories
     * @authenticated
     * @urlParam id int required The ID of the category. Example: 1
     * @response 200 {"message": "Category berhasil dihapus!"}
     * @response 403 {"message": "Unauthorized: You can only delete categories of your own products"}
     * @response 404 {"message": "Category tidak ditemukan"}
     * @response 500 {"message": "Database error while deleting category"}
     */
    public function destroy(int $id)
    {
        try {
            $category = Kategori::with('product')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category tidak ditemukan',
                ], 404);
            }

            // Check if user owns the product that this category belongs to
            if ($category->product->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized: You can only delete categories of your own products',
                ], 403);
            }

            $category->delete();

            return response()->json([
                'message' => 'Category berhasil dihapus!',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Category delete database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while deleting category',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Category delete unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
