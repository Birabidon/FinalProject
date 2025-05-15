<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CanChangeUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckAdmin;

Route::get('/', [PostController::class, 'getAllPostsMarkers'])->name('home');
Route::inertia('/about', 'about')->name('about');

// redirect from just /users/{user} to /users/{user}?tab=posts
Route::get('users/{user}', function (User $user) {
    if (!request()->has('tab')) { // if there is no tab in the request
        return redirect()->to("/users/{$user->id}?tab=posts");
    }
    // if there is
    return app(UserController::class)->show($user, request());
})->name('users.show');

Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::middleware('auth')->group(function () {
    Route::post('/logout ', [AuthController::class, 'logout'])->name('logout');

    Route::resource('locations', LocationController::class);

    Route::resource('posts', PostController::class)->except('update');

    Route::post('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::post('/posts/{post}/rate', [PostController::class, 'rate'])->name('posts.react');

//    Route::get('/users/{user}/posts', [PostController::class, 'getUserPosts'])->name('user.posts'); implement in future

    Route::middleware(CanChangeUser::class)->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('users.register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login ', [AuthController::class, 'login']);

});


