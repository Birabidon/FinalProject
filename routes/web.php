<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckAdmin;

Route::get('/', [PostController::class, 'getAllPostsMarkers'])->name('home');
Route::inertia('/about', 'about')->name('about');



Route::middleware('auth')->group(function () {
    Route::post('/logout ', [AuthController::class, 'logout'])->name('users.logout');
    Route::resource('locations', LocationController::class);
    Route::resource('posts', PostController::class);
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(CheckAdmin::class)->name('users.destroy');
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('users.register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('users.login');
    Route::post('/login ', [\App\Http\Controllers\AuthController::class, 'login']);

});


