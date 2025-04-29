<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{

    public function getAllPostsMarkers(Request $request)
    {
        $postId = $request->query('post');

        $data = [
            'postsMarkers' => Post::with('user')->get(['id', 'lat', 'lng']),
            'can' => [],
        ];

        if ($postId) {
            $post = Post::find($postId)->load('user');
            $data['post'] = $post;
            $data['can']['edit'] = auth()->user() ? auth()->user()->can('update', $post) : false;
            $data['can']['delete'] = auth()->user() ? auth()->user()->can('delete', $post) : false;
        }

        return inertia('home', $data);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->paginate(10);

        return inertia('Post/Index', ['posts' => $posts]);
    }

    public function getUserPosts(User $user)
    {
        $posts = $user->posts()->get();

        return Inertia::render('User/Profile/ShowUserPosts', [
            'posts' => $posts,
            'user' => $user,
        ]);
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
        $post->load('user');
        $can = [
            'edit' => auth()->user() ? auth()->user()->can('update', $post) : false,
            'delete' => auth()->user() ? auth()->user()->can('delete', $post) : false,
        ];

        return inertia('Post/Show', ['post' => $post, 'can' => $can]);
    }

    public function rate(Request $request, Post $post) {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // add if rating is the same it would be deleted
        try {
            PostReaction::updateOrCreate(
                ['post_id' => $post->id, 'user_id' => auth()->id()],
                ['rating' => $data['rating']]
            );
        } catch (\Exception $e) {
            return back()->with([
                'error' => 'Rate failed: ' . $e->getMessage()
            ]);
        }

        return back()->with([
            'success' => 'rating saved'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return inertia('Post/Edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validate
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $isUpdated = $post->update($fields);

        if($isUpdated) {
            return redirect()->route('posts.show', $post)->with(['success' => 'Changes saved successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Failed to save changes']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Failed to delete post: ' . $e->getMessage()]);
        }
        return redirect()->route('home')->with(['success' => 'Post deleted successfully']);
    }
}
