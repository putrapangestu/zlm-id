<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $articles = $query->latest('date')->latest('created_at')->paginate(10)->withQueryString();

        $categories = ['Panduan', 'Review', 'Tips & Trik', 'Berita'];

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create(): View
    {
        $categories = ['Panduan', 'Review', 'Tips & Trik', 'Berita'];
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Panduan,Review,Tips & Trik,Berita',
            'author' => 'required|string|max:100',
            'date' => 'required|date',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('articles', 'public');
            $validated['thumbnail'] = $path;
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function show(string $id): View
    {
        $article = Article::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        return view('admin.articles.show', compact('article'));
    }

    public function edit(string $id): View
    {
        $article = Article::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $categories = ['Panduan', 'Review', 'Tips & Trik', 'Berita'];
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $article = Article::where('id', $id)->orWhere('slug', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Panduan,Review,Tips & Trik,Berita',
            'author' => 'required|string|max:100',
            'date' => 'required|date',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail && !str_starts_with($article->thumbnail, 'http')) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $path = $request->file('thumbnail')->store('articles', 'public');
            $validated['thumbnail'] = $path;
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $article = Article::where('id', $id)->orWhere('slug', $id)->firstOrFail();

        if ($article->thumbnail && !str_starts_with($article->thumbnail, 'http')) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
