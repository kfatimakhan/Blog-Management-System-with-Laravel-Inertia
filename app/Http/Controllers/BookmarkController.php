<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleBookmark(Post $post)
    {
        $bookmark = $post->bookmarks()->where('user_id', auth()->id())->first();

        if ($bookmark) {
            $bookmark->delete();
            $message = 'Post removed from bookmarks!';
            $isBookmarked = false;
        } else {
            $bookmark = new Bookmark();
            $bookmark->user_id = auth()->id();
            $bookmark->post_id = $post->id;
            $bookmark->save();
            $message = 'Post bookmarked successfully!';
            $isBookmarked = true;
        }

        return response()->json([
            'is_bookmarked' => $isBookmarked,
            'message' => $message
        ]);
    }

    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()->with('post.user')->paginate(10);

        return Inertia::render('Bookmarks/Index', [
            'bookmarks' => $bookmarks
        ]);
    }
}
