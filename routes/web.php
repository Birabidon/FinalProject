<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MarkerController::class, 'index']);
Route::inertia('about', 'about');
Route::inertia('login', 'Auth/Login');
