<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search');

        $query = Article::query()->published();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if (!empty($category) && $category !== 'all' && $category !== 'Semua') {
            $query->where('category', $category);
        }

        // Get featured article (most recent) and 2 secondary articles for headline section
        $featured = null;
        $secondary = collect([]);
        if (empty($search) && ($category === 'all' || $category === 'Semua') && $request->get('page', 1) == 1) {
            $topThree = (clone $query)->latest('date')->latest('created_at')->take(3)->get();
            $featured = $topThree->first();
            $secondary = $topThree->slice(1)->values();
        }

        $articlesQuery = clone $query;
        if ($featured) {
            $excludeIds = collect([$featured->id])->merge($secondary->pluck('id'));
            $articlesQuery->whereNotIn('id', $excludeIds);
        }

        $articles = $articlesQuery->latest('date')->latest('created_at')->paginate(6)->withQueryString();

        $categories = ['Panduan', 'Review', 'Tips & Trik', 'Berita'];

        return view('landing.articles', compact('featured', 'secondary', 'articles', 'categories', 'category', 'search'));
    }

    public function show(string $slug): View|RedirectResponse
    {
        $article = Article::where('slug', $slug)
            ->orWhere('id', $slug)
            ->published()
            ->firstOrFail();

        // 301 Permanent Redirect if accessed via numeric ID
        if (!empty($article->slug) && $slug === (string) $article->id) {
            return redirect()->route('landing.article-detail', $article->slug, 301);
        }

        // Increment views count
        $article->increment('views_count');

        // Related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('date')
            ->take(3)
            ->get();

        if ($relatedArticles->count() < 3) {
            $moreArticles = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('date')
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->merge($moreArticles);
        }

        return view('landing.article-detail', compact('article', 'relatedArticles'));
    }
}
