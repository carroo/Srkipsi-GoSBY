<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\TripCartController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourismController as AdminTourismController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DistanceMatrixController;
use App\Http\Controllers\Admin\ItineraryController as AdminItineraryController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/tourism', [TourismController::class, 'index'])->name('tourism.index');
Route::get('/tourism/{id}', [TourismController::class, 'show'])->name('tourism.show');

// Authentication Routes - User
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Authentication Routes - Admin
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login.redirect');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');

Route::get('/itinerary/result/{id}', [ItineraryController::class, 'result'])->name('itinerary.result');
// Trip Cart Routes (Protected)
Route::middleware('auth:web')->group(function () {
    Route::post('/trip-cart/add', [TripCartController::class, 'add'])->name('trip-cart.add');
    Route::delete('/trip-cart/remove/{id}', [TripCartController::class, 'remove'])->name('trip-cart.remove');
    Route::get('/trip-cart', [TripCartController::class, 'index'])->name('trip-cart.index');

    // Itinerary Routes
    Route::get('/itinerary/create', [ItineraryController::class, 'create'])->name('itinerary.create');
    Route::get('/itinerary/list', [ItineraryController::class, 'list'])->name('itinerary.list');
    Route::post('/itinerary/generate', [ItineraryController::class, 'generate'])->name('itinerary.generate');
    Route::post('/itinerary/save', [ItineraryController::class, 'save'])->name('itinerary.save');
    Route::get('/itinerary/{id}', [ItineraryController::class, 'show'])->name('itinerary.show');
    Route::delete('/itinerary/{id}', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
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

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Distance Matrix
    Route::get('/distance-matrix', [DistanceMatrixController::class, 'index'])->name('distance-matrix.index');
    Route::get('/distance-matrix/data', [DistanceMatrixController::class, 'getDistanceData'])->name('distance-matrix.data');

    // Itinerary Management
    Route::get('/itinerary', [AdminItineraryController::class, 'index'])->name('itinerary.index');
    Route::delete('/itinerary/{id}', [AdminItineraryController::class, 'destroy'])->name('itinerary.destroy');

});
