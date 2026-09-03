<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Laptop;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $laptops = Laptop::query()
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->select('id', 'slug', 'updated_at')
            ->latest('updated_at')
            ->get();

        $articles = Article::query()
            ->published()
            ->whereNotNull('slug')
            ->select('id', 'slug', 'updated_at')
            ->latest('updated_at')
            ->get();

        $content = view('sitemap', compact('laptops', 'articles'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
