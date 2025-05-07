<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{

    public function getAllPostsMarkers(Request $request)
    {
        $postId = $request->query('post');

        $data = [
            'postsMarkers' => Post::all(['id', 'lat', 'lng'])->map(function ($post) {
                return [ // to not include appended attributes in Post model
                    'id' => $post->id,
                    'lat' => $post->lat,
                    'lng' => $post->lng,
                ];
            }),
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
            'location' => ['sometimes', 'string', 'max:255', 'nullable'],
            'lat' => ['sometimes', 'numeric', 'nullable'],
            'lng' => ['sometimes', 'numeric', 'nullable'],
        ]);

        $data = [];

        if (isset($fields['location'])) $data['location'] = $fields['location'];
        if (isset($fields['lat'])) $data['lat'] = (float)$fields['lat'];
        if (isset($fields['lng'])) $data['lng'] = (float)$fields['lng'];

        return inertia('Post/Create', $data);
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
            'images' => [
                'array',
                'nullable',
                'max:10',
                function ($attribute, $value, $fail) {
                    // Custom rule to check the total size of all files
                    $totalSize = collect($value)->sum(fn(UploadedFile $file) => $file->getSize());

                    if ($totalSize > 1024 * 1024 * 1024) {
                        $fail('The total size of all files must not exceed 1GB.');
                    }
                },
            ],
            'images.*' => [
                'image',                          // Ensures it's an image file
                'mimes:jpeg,png,jpg,gif',         // Restricts file types
                'max:2048',                       // Limits file size (2MB)
            ],
        ]);

//        dd($fields);

//        $fields['created_by'] = auth()->id();
//        $fields['updated_by'] = auth()->id();
//
//        Post::create($fields);

        $fields['created_by'] = auth()->id();
        $fields['updated_by'] = auth()->id();

        // Handle image uploads and replace temp URLs
        if ($request->hasFile('images')) {
            $content = $fields['content'];
            $imagesToSave = [];

            foreach ($request->file('images') as $image) {
                // Save file to disk
                $path = Storage::disk('public')->put('attachments', $image);

                // Create attachment record
                $imagesToSave[] = [
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'file_type' => $image->getMimeType(),
                    'created_by' => auth()->id(),
                ];

                // Replace temporary URLs in content
                // This is a simple approach - you might need more specific URL matching
                $tempUrlPattern = '/blob:http[s]?:\/\/[^\s"\']+/';
                $content = preg_replace($tempUrlPattern, '/storage/'.$path, $content, 1);
            }

            $fields['content'] = $content;
        }


        $post = Post::create($fields);

        if (!empty($imagesToSave)) {
            foreach ($imagesToSave as $image) {
                $image['post_id'] = $post->id; // Associate the image with the post
                PostAttachment::create($image);
            }
        }
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
            if ($post->user_rating == $data['rating']) {
                PostReaction::where('post_id', $post->id)->where('user_id', auth()->id())->delete();
            } else {
                PostReaction::updateOrCreate(
                    ['post_id' => $post->id, 'user_id' => auth()->id()],
                    ['rating' => $data['rating']]
                );
            }
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
            'images' => [
                'array',
                'nullable',
                'max:10',
                function ($attribute, $value, $fail) {
                    // Custom rule to check the total size of all files
                    $totalSize = collect($value)->sum(fn(UploadedFile $file) => $file->getSize());

                    if ($totalSize > 1024 * 1024 * 1024) {
                        $fail('The total size of all files must not exceed 1GB.');
                    }
                },
            ],
            'images.*' => [
                'image',                          // Ensures it's an image file
                'mimes:jpeg,png,jpg,gif',         // Restricts file types
                'max:2048',                       // Limits file size (2MB)
            ],
        ]);

        // If there is no file url in content, remove attachment
        foreach($post->attachments()->get() as $attachment) {
            $filename = basename($attachment->file_path);
            $pattern = 'src="/storage/' . $attachment->file_path . '"';

            if (!str_contains($fields['content'], $pattern)) {
                $attachment->delete();
            }
        }

        // Handle image uploads and replace temp URLs
        if ($request->hasFile('images')) {
            $content = $fields['content'];
            $imagesToSave = [];

            foreach ($request->file('images') as $image) {
                // Save file to disk
                $path = Storage::disk('public')->put('attachments', $image);

                // Create attachment record
                $imagesToSave[] = [
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'file_type' => $image->getMimeType(),
                    'created_by' => auth()->id(),
                ];

                // Replace temporary URLs in content
                $tempUrlPattern = '/blob:http[s]?:\/\/[^\s"\']+/';
                $content = preg_replace($tempUrlPattern, '/storage/'.$path, $content, 1);
            }

            $fields['content'] = $content;
        }

        $isUpdated = $post->update($fields);

        if (!empty($imagesToSave)) {
            foreach ($imagesToSave as $image) {
                $image['post_id'] = $post->id; // Associate the image with the post
                PostAttachment::create($image);
            }
        }

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
            $attachments = $post->attachments()->get();

            foreach ($attachments as $attachment) {
                // Delete the attachment record from the database
                $attachment->delete();
            }

            $post->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Failed to delete post: ' . $e->getMessage()]);
        }
        return redirect()->route('home')->with(['success' => 'Post deleted successfully']);
    }
}
