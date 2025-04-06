<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'delete_user' =>
                Auth::user() ?
                Auth::user()->can('deleteOthers', User::class) : // if user is logged in, check if user can delete user (if he is an admin), by delete function in UserPolicy.php
                null
        ];

        return inertia('User/Index', [
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
        $can = [
            'edit' =>
                Auth::user() ?
                    $user->can('isSelf', User::class) || Auth::user()->can('isAdmin', User::class) : // if user is logged in, check if user can edit user (if he is an admin), by update function in UserPolicy.php
                    null,
        ];

        return inertia('User/Show', [
            'user' => $user,
            'can' => $can
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return inertia('User/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate
        $fields = $request->validate([
            'avatar' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:3', 'confirmed'],
        ]);

        if ($request->hasFile('avatar')) {
            $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $isUpdated = $user->update($fields);

        if($isUpdated) {
            return redirect()->route('users.show', $user)->with(['success' => 'Changes saved successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Failed to save changes']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Failed to delete user: ' . $e->getMessage()]);
            // return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500); // more for API
        }
        return redirect()->route('home')->with(['success' => 'User deleted successfully']);
    }
}
