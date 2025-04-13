<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'featuredPosts' => Post::with(['user', 'tags'])
                ->where('visibility', 'public')
                ->where('is_featured', true)
                ->latest()
                ->take(3)
                ->get(),
            'posts' => Post::with(['user', 'tags'])
                ->where('visibility', 'public')
                ->latest()
                ->paginate(10),
            'filters' => request()->only(['search'])
        ]);
    }
}
