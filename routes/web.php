<?php

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaptopController as AdminLaptopController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QcController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RestockController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProofUploadController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SmartSearchController;
use App\Http\Controllers\TrackingController;
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
Route::get('/smart-search', [SmartSearchController::class, 'index'])->name('landing.smart-search');
Route::post('/smart-search', [SmartSearchController::class, 'search'])->name('landing.smart-search.post');
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

// Kontak Kami (Landing Page)
Route::get('/kontak-kami', [ContactController::class, 'index'])->name('landing.contact');
Route::post('/kontak-kami', [ContactController::class, 'store'])->name('landing.contact.store');

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
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [TrackingController::class, 'trackByNumber'])->name('tracking.by-number');

/*
|--------------------------------------------------------------------------
| Offline-First POS Kasir Routes (Admin & Karyawan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index')->middleware('can:pos.access');
    Route::get('/bootstrap', [PosController::class, 'bootstrap'])->name('bootstrap');
    Route::post('/sync', [PosController::class, 'sync'])->name('sync');
});

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
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
    Route::post('/orders/{order}/returns', [ReturnController::class, 'storeCustomerReturn'])->name('orders.returns.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // Tracking (authenticated)
    Route::get('/tracking/{order}', [TrackingController::class, 'show'])->name('tracking.show');

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
| Admin & Employee Control Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|karyawan'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quality Control (QC)
    Route::prefix('qc')->name('qc.')->group(function () {
        Route::get('/', [QcController::class, 'index'])->name('index')->middleware('can:qc.view');
        Route::get('/{item}/inspect', [QcController::class, 'inspect'])->name('inspect')->middleware('can:qc.inspect');
        Route::post('/{item}/approve', [QcController::class, 'approve'])->name('approve')->middleware('can:qc.inspect');
        Route::post('/{item}/reject', [QcController::class, 'reject'])->name('reject')->middleware('can:qc.inspect');
    });

    // Restock Barang & Dot Matrix Print
    Route::prefix('restocks')->name('restocks.')->group(function () {
        Route::get('/', [RestockController::class, 'index'])->name('index')->middleware('can:restock.view');
        Route::get('/create', [RestockController::class, 'create'])->name('create')->middleware('can:restock.create');
        Route::post('/', [RestockController::class, 'store'])->name('store')->middleware('can:restock.create');
        Route::get('/{restock}', [RestockController::class, 'show'])->name('show')->middleware('can:restock.view');
        Route::get('/{restock}/print-dotmatrix', [RestockController::class, 'printDotMatrix'])->name('print')->middleware('can:restock.print');
    });

    // Retur Barang (Customer & Supplier)
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index')->middleware('can:returns.view');
        Route::get('/create-supplier', [ReturnController::class, 'createSupplierReturn'])->name('create-supplier')->middleware('can:returns.process');
        Route::post('/supplier', [ReturnController::class, 'storeSupplierReturn'])->name('store-supplier')->middleware('can:returns.process');
        Route::get('/{return}', [ReturnController::class, 'show'])->name('show')->middleware('can:returns.view');
        Route::post('/{return}/process', [ReturnController::class, 'process'])->name('process')->middleware('can:returns.process');
    });

    // Member & Loyalitas
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index')->middleware('can:members.view');
        Route::get('/{user}', [MemberController::class, 'show'])->name('show')->middleware('can:members.view');
        Route::post('/{user}/points', [MemberController::class, 'adjustPoints'])->name('points')->middleware('can:members.manage');
    });

    // Users & Employee Granular Permissions
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('can:users.manage');
        Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('can:users.manage');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('can:users.manage');
        Route::get('/{user}', [UserController::class, 'show'])->name('show')->middleware('can:users.manage');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit')->middleware('can:users.manage');
        Route::put('/{user}', [UserController::class, 'update'])->name('update')->middleware('can:users.manage');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('can:users.manage');
    });

    // Products / Laptops
    Route::get('/laptops/api/templates', [AdminLaptopController::class, 'apiSearchTemplates'])->name('laptops.api.templates');
    Route::patch('/laptops/{laptop}/toggle-status', [AdminLaptopController::class, 'toggleStatus'])->name('laptops.toggle-status');
    Route::resource('laptops', AdminLaptopController::class)->except(['show']);
    Route::get('/laptops/{laptop}', [AdminLaptopController::class, 'show'])->name('laptops.show');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Add-Ons & Bundling
    Route::patch('/addons/{addon}/toggle-recommended', [AddonController::class, 'toggleRecommended'])->name('addons.toggle-recommended');
    Route::patch('/addons/{addon}/toggle-active', [AddonController::class, 'toggleActive'])->name('addons.toggle-active');
    Route::resource('addons', AddonController::class)->except(['show']);

    // Articles & Blog
    Route::resource('articles', ArticleController::class);

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index')->middleware('can:transactions.view');
        Route::get('/create', [TransactionController::class, 'create'])->name('create')->middleware('can:transactions.view');
        Route::post('/', [TransactionController::class, 'store'])->name('store')->middleware('can:transactions.view');
        Route::get('/{order}', [TransactionController::class, 'show'])->name('show')->middleware('can:transactions.view');
        Route::post('/{order}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('confirm-payment')->middleware('can:transactions.confirm');
    });

    // Testimonials & Sliders
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('sliders', SliderController::class)->except(['show']);

    // Orders redirect
    Route::permanentRedirect('/orders', '/admin/transactions')->name('orders.redirect');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderStatusController::class, 'update'])->name('orders.status');
    Route::get('/orders/{order}/tracking', [App\Http\Controllers\Admin\OrderStatusController::class, 'tracking'])->name('orders.tracking');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases')->middleware('can:reports.purchases');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss')->middleware('can:reports.profit_loss');
        Route::get('/product-stats', [ReportController::class, 'productStats'])->name('product-stats')->middleware('can:reports.product_stats');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index')->middleware('can:settings.manage');
        Route::post('/', [SettingController::class, 'update'])->name('update')->middleware('can:settings.manage');
        Route::post('/test-wa', [SettingController::class, 'testWhatsApp'])->name('test-wa')->middleware('can:settings.manage');
    });
});

require __DIR__.'/auth.php';
