<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        return PostResource::collection(
            Post::with(['user', 'tags'])
                ->where('visibility', 'public')
                ->latest()
                ->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255'
        ]);

        $post = $request->user()->posts()->create($request->only([
            'title', 'content', 'visibility'
        ]));

        if ($request->tags) {
            $post->syncTags($request->tags);
        }

        return new PostResource($post->load(['user', 'tags']));
    }

    // ... other CRUD methods
}
