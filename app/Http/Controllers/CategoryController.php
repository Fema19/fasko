<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return $this->view('categories.index', compact('categories'));
    }

    public function create()
    {
        return $this->view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Category::create($request->only('name'));

        return redirect()
            ->route('admin.categories.index')   // ✔ route benar
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        return $this->view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category->update($request->only('name'));

        return redirect()
            ->route('admin.categories.index')  // ✔ disesuaikan
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')  // ✔ back diganti agar konsisten
            ->with('success', 'Kategori berhasil dihapus');
    }
}
