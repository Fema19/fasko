<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'type' => 'required|in:unit,capacity',
        ]);

        Category::create($request->only('name','type'));

        $prefix = Auth::user()->role === 'guru' ? 'guru' : 'admin';

        return redirect()
            ->route($prefix.'.categories.index')
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
            'type' => 'required|in:unit,capacity',
        ]);

        $category->update($request->only('name','type'));

        $prefix = Auth::user()->role === 'guru' ? 'guru' : 'admin';

        return redirect()
            ->route($prefix.'.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        $prefix = Auth::user()->role === 'guru' ? 'guru' : 'admin';

        return redirect()
            ->route($prefix.'.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
