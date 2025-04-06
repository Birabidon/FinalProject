<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckSelfAndAdmin;
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
    Route::resource('users', UserController::class)->except(['destroy', 'update', 'edit']);
    Route::middleware(CheckSelfAndAdmin::class)->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('users.register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('users.login');
    Route::post('/login ', [AuthController::class, 'login']);

});


