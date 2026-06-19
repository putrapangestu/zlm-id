<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaptopController as AdminLaptopController;
use App\Http\Controllers\Admin\LaptopVariantController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProofUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\XenditWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LaptopController::class, 'index'])->name('landing.home');
Route::get('/search', [LaptopController::class, 'search'])->name('landing.search');
Route::get('/smart-search', [App\Http\Controllers\SmartSearchController::class, 'index'])->name('landing.smart-search');
Route::post('/smart-search', [App\Http\Controllers\SmartSearchController::class, 'search'])->name('landing.smart-search.post');
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
Route::get('/articles/{id}', function ($id) {
    return view('landing.article-detail');
})->name('landing.article-detail');

Route::get('/testimonials', function () {
    $testimonials = App\Models\Testimonial::where('is_active', true)->latest()->paginate(12);
    return view('landing.testimonials', compact('testimonials'));
})->name('landing.testimonials');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/laptop/{laptop}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle')->middleware('auth');

// Xendit Webhook (public, no auth, no CSRF)
Route::post('/webhooks/xendit', [XenditWebhookController::class, 'handle'])
    ->name('webhooks.xendit')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Tracking (public)
Route::get('/tracking', [\App\Http\Controllers\TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [\App\Http\Controllers\TrackingController::class, 'trackByNumber'])->name('tracking.by-number');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('landing.checkout');
    Route::post('/orders', [OrderController::class, 'placeOrder'])->name('orders.place');
    Route::get('/orders/{order}', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders/{order}/xendit/callback', [OrderController::class, 'xenditCallback'])->name('orders.xendit.callback');
    Route::post('/orders/{order}/proof', [ProofUploadController::class, 'upload'])->name('orders.proof.upload');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // Tracking (authenticated)
    Route::get('/tracking/{order}', [\App\Http\Controllers\TrackingController::class, 'show'])->name('tracking.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shipping (RajaOngkir)
    Route::get('/shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
    Route::get('/shipping/cities', [ShippingController::class, 'cities'])->name('shipping.cities');
    Route::post('/shipping/cost', [ShippingController::class, 'cost'])->name('shipping.cost');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (login, register, reset password, etc.)
|--------------------------------------------------------------------------
*/

// Auth routes are defined in routes/auth.php (required at bottom of this file)

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Logged out successfully');
})->name('auth.logout');

/*
|--------------------------------------------------------------------------
| Admin Routes — New Modules (from merge)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products (new module from merge)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Articles (new module from merge)
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
    });

    // Transactions (new module from merge)
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{order}', [TransactionController::class, 'show'])->name('show');
        Route::post('/{order}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('confirm-payment');
    });

    // Users (new module from merge)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Laptops (existing module)
    Route::resource('laptops', AdminLaptopController::class)->except(['show']);
    Route::get('/laptops/{laptop}', [AdminLaptopController::class, 'show'])->name('laptops.show');
    Route::resource('laptops.variants', LaptopVariantController::class)->shallow();

    // Categories (existing module)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Testimonials
    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    // Orders (existing module)
    Route::get('/orders', function () {
        $orders = App\Models\Order::with('user', 'items')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    })->name('orders.index');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderStatusController::class, 'update'])->name('orders.status');
    Route::get('/orders/{order}/tracking', [App\Http\Controllers\Admin\OrderStatusController::class, 'tracking'])->name('orders.tracking');

    // Customers (existing module)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/purchases', [\App\Http\Controllers\Admin\ReportController::class, 'purchases'])->name('purchases');
        Route::get('/profit-loss', [\App\Http\Controllers\Admin\ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/product-stats', [\App\Http\Controllers\Admin\ReportController::class, 'productStats'])->name('product-stats');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
