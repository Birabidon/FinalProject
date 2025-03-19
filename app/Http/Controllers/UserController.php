<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query) use ($request) { // (when) if search is not empty, use is for accessing the request inside the function
            $query
                ->where('name', 'like','%'.$request->search.'%') // where users name like %<-search->%
                ->orWhere('email', 'like','%'.$request->search.'%'); // or where users email like %<-search->%
        })->paginate(5)->withQueryString(); // withQueryString() to keep the query string in the pagination links

        $searchTerm = $request->search; // to put the search term in the search input on page

        $can = [
            'delete_user' => Auth::user() ?
                Auth::user()->can('delete', User::class) : // if user is logged in, check if user can delete user (if he is an admin), by delete function in UserPolicy.php
                null
        ];

        return inertia('UsersList', [
            'users' => $users,
            'searchTerm' =>$searchTerm,
            'can' => $can
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
