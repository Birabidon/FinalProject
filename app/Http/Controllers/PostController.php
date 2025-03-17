<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function getAllPostsMarkers()
    {
        $markers = Post::all('title', 'content', 'lat', 'lng', 'location', 'created_by', 'created_at');

        return inertia('home', ['postsMarkers' => $markers]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->paginate(10);

        return inertia('Posts/Index', ['posts' => $posts]);
    }

    public function indexWithLocation()
    {
        $posts = Post::with('location')->get();
        return inertia('Post/IndexWithLocation', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'location' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        return inertia('Post/Create', [
            'location' => $fields['location'],
            'lat' => (float)$fields['lat'],
            'lng' => (float)$fields['lng']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $fields['created_by'] = auth()->id();
        $fields['updated_by'] = auth()->id();

        Post::create($fields);

        return redirect()->route('home')->withMessage('Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
