<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    /**
     * List all products
     *
     * Retrieve a list of all products with their user and category information.
     * Requires API token authentication.
     *
     * @tags Products
     * @authenticated
     * @response 200 {"message": "Product list retrieved successfully", "data": [{"id": 1, "user_id": 1, "name": "Product Name", "qty": 10, "price": 50000}]}
     * @response 500 {"message": "Error retrieving products", "error": "error message"}
     */
    public function index()
    {
        try {
            $products = Product::with('user', 'kategoris')->get();
            
            return response()->json([
                'message' => 'Product list retrieved successfully',
                'data'    => $products
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Product index error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error retrieving products',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new product
     *
     * Create a new product associated with the authenticated user.
     * The product will be assigned to the current user automatically.
     * Requires API token authentication.
     *
     * @tags Products
     * @authenticated
     * @bodyParam name string required The name of the product. Example: Laptop Dell XPS
     * @bodyParam quantity int required The quantity of the product. Example: 5
     * @bodyParam price int required The price of the product. Example: 15000000
     * @response 201 {"message": "Produk berhasil ditambahkan!", "data": {"id": 1, "user_id": 1, "name": "Laptop Dell XPS", "qty": 5, "price": 15000000}}
     * @response 422 {"message": "Validation error", "errors": {"name": ["The name field is required."]}}
     * @response 500 {"message": "Database error while creating product"}
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            $validated['qty'] = $validated['quantity'];
            unset($validated['quantity']);

            $product = Product::create($validated);
            $product->load('user', 'kategoris');

            Log::info('Membuat data produk', [
                'list' => $product
            ]);

            return response()->json([
                'message' => 'Produk berhasil ditambahkan!',
                'data'    => $product,
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Product store database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while creating product',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Product store unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details
     *
     * Retrieve a specific product by its ID with user and category information.
     * Requires API token authentication.
     *
     * @tags Products
     * @authenticated
     * @urlParam id int required The ID of the product. Example: 1
     * @response 200 {"message": "Product retrieved successfully", "data": {"id": 1, "user_id": 1, "name": "Laptop Dell XPS", "qty": 5, "price": 15000000, "user": {"id": 1, "name": "John Doe"}, "kategoris": []}}
     * @response 404 {"message": "Product tidak ditemukan"}
     * @response 500 {"message": "Error retrieving product"}
     */
    public function show(int $id)
    {
        try {
            $product = Product::with('user', 'kategoris')->find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Product retrieved successfully',
                'data'    => $product
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data produk', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error retrieving product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product
     *
     * Update the details of a specific product. Only the owner of the product can update it.
     * Requires API token authentication.
     *
     * @tags Products
     * @authenticated
     * @urlParam id int required The ID of the product. Example: 1
     * @bodyParam name string required The updated name of the product. Example: Laptop Dell XPS 15
     * @bodyParam quantity int required The updated quantity. Example: 10
     * @bodyParam price int required The updated price. Example: 16000000
     * @response 200 {"message": "Product updated successfully", "data": {"id": 1, "user_id": 1, "name": "Laptop Dell XPS 15", "qty": 10, "price": 16000000}}
     * @response 403 {"message": "Unauthorized: You can only update your own products"}
     * @response 404 {"message": "Product tidak ditemukan"}
     * @response 422 {"message": "Validation error"}
     * @response 500 {"message": "Database error while updating product"}
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                ], 404);
            }

            // Check if user owns the product
            if ($product->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized: You can only update your own products',
                ], 403);
            }

            $validated = $request->validated();
            $validated['qty'] = $validated['quantity'];
            unset($validated['quantity']);

            $product->update($validated);
            $product->load('user', 'kategoris');

            return response()->json([
                'message' => 'Product updated successfully',
                'data'    => $product
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Product update database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while updating product',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Product update unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product
     *
     * Delete a specific product. Only the owner of the product can delete it.
     * Deleting a product will also delete all associated categories.
     * Requires API token authentication.
     *
     * @tags Products
     * @authenticated
     * @urlParam id int required The ID of the product. Example: 1
     * @response 200 {"message": "Product berhasil dihapus!"}
     * @response 403 {"message": "Unauthorized: You can only delete your own products"}
     * @response 404 {"message": "Product tidak ditemukan"}
     * @response 500 {"message": "Database error while deleting product"}
     */
    public function destroy(int $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product tidak ditemukan',
                ], 404);
            }

            // Check if user owns the product
            if ($product->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized: You can only delete your own products',
                ], 403);
            }

            $product->delete();

            return response()->json([
                'message' => 'Product berhasil dihapus!',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Product delete database error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Database error while deleting product',
                'error'   => $e->getMessage()
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Product delete unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
