<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags', 'comments.user', 'likes'])
            ->where('visibility', 'public')
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('tags', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%");
                  });
            });
        }

        $posts = $query->paginate(10);

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'search' => $request->search ?? ''
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255'
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->visibility = $request->visibility;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post_images', 'public');
            $post->image = $path;
        }

        $post->save();

        if ($request->tags) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => Str::lower($tagName)]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        if ($post->visibility === 'private' && $post->user_id !== auth()->id()) {
            abort(403);
        }

        $post->load(['user', 'tags', 'comments.user', 'likes.user']);

        return Inertia::render('Posts/Show', [
            'post' => $post,
            'isBookmarked' => auth()->check() ? $post->bookmarks()->where('user_id', auth()->id())->exists() : false,
            'isLiked' => auth()->check() ? $post->likes()->where('user_id', auth()->id())->exists() : false
        ]);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render('Posts/Edit', [
            'post' => $post->load('tags')
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255'
        ]);

        $post->title = $request->title;
        $post->content = $request->content;
        $post->visibility = $request->visibility;

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('post_images', 'public');
            $post->image = $path;
        }

        $post->save();

        if ($request->tags) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => Str::lower($tagName)]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Post deleted successfully!');
    }
}
