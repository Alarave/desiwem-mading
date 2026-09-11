<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function indexView()
    {
        $categories = Category::withCount('articles')->latest()->get();
        return view('admin.categories', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Kategori berhasil ditambahkan',
                'category' => $category
            ], 201);
        }

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Kategori berhasil diperbarui',
                'category' => $category
            ]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Request $request, Category $category)
    {
        if ($category->articles()->count() > 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Kategori masih memiliki artikel terkait');
        }

        $category->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Kategori berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
