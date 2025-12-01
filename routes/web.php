<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TourismController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/tourism', [TourismController::class, 'index'])->name('tourism.index');
Route::get('/tourism/{id}', [TourismController::class, 'show'])->name('tourism.show');
Route::post('/tourism/saw', [TourismController::class, 'saw'])->name('tourism.saw');
Route::get('/tourism-calculation', [TourismController::class, 'showCalculation'])->name('tourism.calculation');
