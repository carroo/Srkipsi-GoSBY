<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/app', function () {
    return view('app');
});
