<?php

use App\Http\Controllers\Admin\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [LaptopController::class, 'index'])->name('landing.home');
Route::get('/search', [LaptopController::class, 'search'])->name('landing.search');
Route::get('/compare', [LaptopController::class, 'compare'])->name('landing.compare');
Route::get('/laptop/{id}', [LaptopController::class, 'show'])->name('landing.detail');
Route::get('/checkout', [LaptopController::class, 'checkout'])->name('landing.checkout');
Route::get('/profile', [LaptopController::class, 'profile'])->name('landing.profile');
Route::get('/articles', function () {
    return view('landing.articles');
})->name('landing.articles');

Route::get('/articles/{id}', function ($id) {
    return view('landing.article-detail');
})->name('landing.article-detail');

// Auth Routes
Auth::routes();

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Logged out successfully');
})->name('auth.logout');

// Admin Routes - protected by auth + role:admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('layouts.dashboard');
    })->name('dashboard');

    // Admin Product Management Routes
    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Admin Article Management Routes
    Route::prefix('articles')->name('admin.articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('transactions')->name('admin.transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        // Additional transaction routes (show, edit, etc.) can be added here
    });

    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        // Additional user routes (show, edit, etc.) can be added here
    });
});
