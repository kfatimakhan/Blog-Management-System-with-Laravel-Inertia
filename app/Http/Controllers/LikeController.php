<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Events\PostLiked;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleLike(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $message = 'Post unliked successfully!';
        } else {
            $like = new Like();
            $like->user_id = auth()->id();
            $like->post_id = $post->id;
            $like->save();
            $message = 'Post liked successfully!';

            // Broadcast the like event
            broadcast(new PostLiked($post, auth()->user()))->toOthers();
        }

        return response()->json([
            'likes_count' => $post->likes()->count(),
            'is_liked' => !$like,
            'message' => $message
        ]);
    }
}
