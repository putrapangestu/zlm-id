<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaptopController;

Route::get('/', [LaptopController::class, 'index'])->name('landing.home');
Route::get('/search', [LaptopController::class, 'search'])->name('landing.search');
Route::get('/compare', [LaptopController::class, 'compare'])->name('landing.compare');
Route::get('/laptop/{id}', [LaptopController::class, 'show'])->name('landing.detail');
Route::get('/checkout', [LaptopController::class, 'checkout'])->name('landing.checkout');
Route::get('/profile', [LaptopController::class, 'profile'])->name('landing.profile');
Route::get('/articles', function () {
    return view('landing.articles');
})->name('landing.articles');

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login');

Route::post('/logout', function () {
    // auth()->logout();
    return redirect('/')->with('success', 'Logged out successfully');
})->name('auth.logout');

Route::get('/admin', function () {
    return view('layouts.dashboard');
});
