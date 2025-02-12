<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', [\App\Http\Controllers\MarkerController::class, 'index']);
Route::inertia('/about', 'about');

Route::get('/users', function (Request $request) {
    return inertia('UsersList', [
        'users' => User::when($request->search, function ($query) use ($request) { // (when) if search is not empty, use is for accessing the request inside the function
            $query
            ->where('name', 'like','%'.$request->search.'%') // where users name like %<-search->%
            ->orWhere('email', 'like','%'.$request->search.'%'); // or where users email like %<-search->%
        })->paginate(5)->withQueryString(),// withQueryString() to keep the query string in the pagination links 

        'searchTerm' => $request->search, // to put the search term in the search input on page

        'can' => [ 
            'delete_user' => Auth::user() ? 
                Auth::user()->can('delete', User::class) : // if user is logged in, check if user can delete user (if he is an admin), by delete funktion in UserPolicy.php
                null
        ]
    ]);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout ', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::inertia('/register', 'Auth/Register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

    Route::inertia('/login', 'Auth/Login');
    Route::post('/login ', [\App\Http\Controllers\AuthController::class, 'login']);

});


