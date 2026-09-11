<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function publicIndex(Request $request, $tag = null)
    {
        $query = Article::with(['category', 'author'])->latest();

        $categories = Category::all();

        $activeCategory = null;
        if ($tag) {
            $activeCategory = $categories->first(function ($c) use ($tag) {
                return $c->name === $tag
                    || strtolower($c->name) === strtolower($tag)
                    || \Illuminate\Support\Str::slug($c->name) === \Illuminate\Support\Str::slug($tag);
            });
            if (!$activeCategory && is_numeric($tag)) {
                $activeCategory = $categories->find($tag);
            }
        } elseif ($request->filled('category_id')) {
            $activeCategory = $categories->find($request->category_id);
        }

        if ($activeCategory) {
            $query->where('category_id', $activeCategory->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->get();

        return view('mading.index', compact('articles', 'categories', 'activeCategory'));
    }

    public function publicShow(Article $article)
    {
        $article->load(['category', 'author']);
        $latestArticles = Article::with(['category', 'author'])
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();

        return view('mading.show', compact('article', 'latestArticles'));
    }

    public function adminIndex()
    {
        $articles = Article::with(['category', 'author'])->latest()->get();
        $categories = Category::all();
        return view('admin.articles', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        $validated['created_by'] = Auth::id();

        $article = Article::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Artikel berhasil dibuat',
                'article' => $article->load(['category', 'author'])
            ], 201);
        }

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil dibuat');
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url|max:500',
        ]);

        $article->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Artikel berhasil diperbarui',
                'article' => $article->load(['category', 'author'])
            ]);
        }

        return redirect()->route('admin.articles')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(Request $request, Article $article)
    {
        $article->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Artikel berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'Artikel berhasil dihapus');
    }
}
