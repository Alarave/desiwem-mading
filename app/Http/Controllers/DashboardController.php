<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalArticles = Article::count();
        $totalUsers = User::count();
        $recentArticles = Article::with(['category', 'author'])->latest()->take(5)->get();
        $categories = Category::withCount('articles')->orderByDesc('articles_count')->take(5)->get();

        // 6 Bulan Terakhir Tren Artikel
        $monthlyTrendLabels = [];
        $monthlyTrendData = [];
        $bulanIndo = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->copy()->subMonths($i);
            $monthlyTrendLabels[] = $bulanIndo[(int)$date->format('n')] . ' ' . $date->format('Y');
            $monthlyTrendData[] = Article::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Distribusi Kategori untuk Donut Chart
        $categoryChartData = Category::withCount('articles')
            ->orderByDesc('articles_count')
            ->take(6)
            ->get();

        $categoryChartLabels = $categoryChartData->pluck('name')->toArray();
        $categoryChartCounts = $categoryChartData->pluck('articles_count')->toArray();

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalArticles',
            'totalUsers',
            'recentArticles',
            'categories',
            'monthlyTrendLabels',
            'monthlyTrendData',
            'categoryChartLabels',
            'categoryChartCounts'
        ));
    }

    public function summary()
    {
        return response()->json([
            'categories' => Category::count(),
            'articles' => Article::count(),
            'users' => User::count(),
        ]);
    }

    public function reportView()
    {
        $categories = Category::withCount('articles')->get();
        $articles = Article::with(['category', 'author'])->latest()->get();
        $articlesThisMonth = Article::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $topCategory = $categories->sortByDesc('articles_count')->first();
        $topAuthor = User::withCount('articles')->orderByDesc('articles_count')->first();
        $totalUsers = User::count();

        return view('admin.report', compact(
            'categories',
            'articles',
            'articlesThisMonth',
            'topCategory',
            'topAuthor',
            'totalUsers'
        ));
    }

    public function reportData()
    {
        $categories = Category::withCount('articles')->get();
        $articles = Article::with(['category', 'author'])->latest()->get();
        $topCategory = $categories->sortByDesc('articles_count')->first();
        $topAuthor = User::withCount('articles')->orderByDesc('articles_count')->first();

        return response()->json([
            'categories' => $categories,
            'articles' => $articles,
            'summary' => [
                'total_categories' => Category::count(),
                'total_articles' => Article::count(),
                'total_users' => User::count(),
                'articles_this_month' => Article::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'top_category' => $topCategory ? $topCategory->name : null,
                'top_author' => $topAuthor ? $topAuthor->name : null,
            ]
        ]);
    }
}
