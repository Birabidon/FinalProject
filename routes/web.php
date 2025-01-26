<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MarkerController::class, 'index']);

Route::inertia('/about', 'about');

Route::inertia('/register', 'Auth/Register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
