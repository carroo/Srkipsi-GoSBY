<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\TripCartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourismController as AdminTourismController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/tourism', [TourismController::class, 'index'])->name('tourism.index');
Route::get('/tourism/{id}', [TourismController::class, 'show'])->name('tourism.show');
Route::post('/tourism/saw', [TourismController::class, 'saw'])->name('tourism.saw');
Route::get('/tourism-calculation', [TourismController::class, 'showCalculation'])->name('tourism.calculation');

// Authentication Routes - User
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Authentication Routes - Admin
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');

// Trip Cart Routes (Protected)
Route::middleware('auth:web')->group(function () {
    Route::post('/trip-cart/add', [TripCartController::class, 'add'])->name('trip-cart.add');
    Route::delete('/trip-cart/remove/{id}', [TripCartController::class, 'remove'])->name('trip-cart.remove');
    Route::get('/trip-cart', [TripCartController::class, 'index'])->name('trip-cart.index');
});

// Admin Routes (Protected)
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tourism Management
    Route::get('/tourism', [AdminTourismController::class, 'index'])->name('tourism.index');
    Route::post('/tourism', [AdminTourismController::class, 'store'])->name('tourism.store');
    Route::get('/tourism/{id}', [AdminTourismController::class, 'show'])->name('tourism.show');
    Route::put('/tourism/{id}', [AdminTourismController::class, 'update'])->name('tourism.update');
    Route::delete('/tourism/{id}', [AdminTourismController::class, 'destroy'])->name('tourism.destroy');
    Route::get('/tourism-import-api', [AdminTourismController::class, 'importFromApi'])->name('tourism.import-api');
    Route::get('/tourism-update-serpapi', [AdminTourismController::class, 'updateSerpData'])->name('tourism.update-serpapi');

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Placeholder routes for sidebar menu (will be implemented later)
    Route::get('/users', function() { return 'Users Management'; })->name('users.index');
    Route::get('/bookings', function() { return 'Bookings Management'; })->name('bookings.index');
    Route::get('/settings', function() { return 'Settings'; })->name('settings');
    Route::get('/profile', function() { return 'Profile'; })->name('profile');
});
