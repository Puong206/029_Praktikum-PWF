<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories with product count
        $categories = Category::withCount('products')->get();
        
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        Gate::authorize('manage-category');
        return view('category.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-category');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ], [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique'   => 'Nama kategori sudah ada.',
                'name.max'      => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            ]);

            Category::create($validated);

            Log::info('Membuat kategori baru', [
                'name' => $validated['name'],
            ]);

            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Throwable $e) {
            Log::error('Error creating category', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat kategori.');
        }
    }

    public function show(Category $category)
    {
        $products = $category->products()->with('user')->get();
        
        return view('category.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        Gate::authorize('manage-category');
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('manage-category');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            ], [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique'   => 'Nama kategori sudah ada.',
                'name.max'      => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            ]);

            $category->update($validated);

            Log::info('Update kategori', [
                'id'   => $category->id,
                'name' => $validated['name'],
            ]);

            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Throwable $e) {
            Log::error('Error updating category', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui kategori.');
        }
    }

    public function destroy(Category $category)
    {
        Gate::authorize('manage-category');

        try {
            $category->delete();

            Log::info('Menghapus kategori', [
                'id'   => $category->id,
                'name' => $category->name,
            ]);

            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil dihapus!');
        } catch (\Throwable $e) {
            Log::error('Error deleting category', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
