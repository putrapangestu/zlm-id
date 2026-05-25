<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaptopController as AdminLaptopController;
use App\Http\Controllers\Admin\LaptopVariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [LaptopController::class, 'index'])->name('landing.home');
Route::get('/search', [LaptopController::class, 'search'])->name('landing.search');
Route::get('/compare', [CompareController::class, 'index'])->name('landing.compare');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{laptop}', [CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');
Route::get('/compare/ids', [CompareController::class, 'ids'])->name('compare.ids');
Route::get('/compare/products', [CompareController::class, 'products'])->name('compare.products');
Route::get('/laptop/{laptop}', [LaptopController::class, 'show'])->name('landing.detail');
Route::get('/articles', function () {
    return view('landing.articles');
})->name('landing.articles');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/laptop/{laptop}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle')->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('landing.checkout');
    Route::post('/orders', [OrderController::class, 'placeOrder'])->name('orders.place');
    Route::get('/orders/{order}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('laptops', AdminLaptopController::class)->except(['show']);
    Route::get('/laptops/{laptop}', [AdminLaptopController::class, 'show'])->name('laptops.show');
    Route::resource('laptops.variants', LaptopVariantController::class)->shallow();

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::get('/orders', function () {
        $orders = App\Models\Order::with('user', 'items')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    })->name('orders.index');

    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderStatusController::class, 'update'])->name('orders.status');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
});

require __DIR__.'/auth.php';
