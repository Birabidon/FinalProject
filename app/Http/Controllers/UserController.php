<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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
                Auth::user()->isAdmin() : // if user is logged in, check if user can delete user (if he is an admin), by delete function in UserPolicy.php
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
    public function show(User $user, Request $request)
    {
        $tab = $request->query('tab', 'posts'); // get the tab from the request, if not set, default to posts
        $search = $request->query('search');

        $can = [
            'edit' =>
                Auth::user() ?
                    Auth::user()->can('update', $user) : // if user is logged in, check if user can edit user (if he is an admin), by update function in UserPolicy.php
                    false
        ];

        $data = [
            'user' => $user,
            'can' => $can,
            'currentTab' => $tab,
            'searchTerm' => $search,
        ];

        switch($tab) {
            case 'posts':
                $query = $user->posts();
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q-> where('title', 'like', "%$search%")
                            ->orWhere('content', 'like', "%$search%")
                            ->orWhere('location', 'like', "%$search%");
                    });
                }
                $data['posts'] = $query->get();
                break;
            case 'info':
                $data['info'] = [
                    'posts_count' => $user->posts()->count(),
                    'account_created' => $user->created_at->format('Y-m-d H:i:s'),
                    'account_age' => $user->created_at->diffForHumans(),
                    'last_updated' => $user->updated_at->format('Y-m-d H:i:s'),
                    'unique_locations' => $user->posts()->distinct('location')->count('location')
                ];
                break;
            case 'rates':
                $query = $user->reactions()->with(['post' => function($q) use ($search) { // only those posts with search term
                    if ($search) {
                        $q->where('title', 'like', "%$search%")
                            ->orWhere('content', 'like', "%$search%")
                            ->orWhere('location', 'like', "%$search%");
                    }
                }]);
                $data['posts'] = $query->get()->pluck('post')->filter(); // pluck - returns only posts, filter - removes null values
                break;
        }

        return inertia('User/Show', $data);
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
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->posts->each(function($post) {
                $post->attachments->each(function($attachment) {
                    $attachment->delete(); // To delete attachments from storage
                });
                $post->delete();
            });

            $isDeleted = $user->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Failed to delete user: ' . $e->getMessage()]);
            // return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500); // more for API
        }
        if ($isDeleted) {
            return redirect()->route('home')->with(['success' => 'User deleted successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Failed to delete user']);
        }
    }
}
