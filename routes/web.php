<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\TripCartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
