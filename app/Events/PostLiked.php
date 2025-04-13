<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('post.'.$this->post->id);
    }

    public function broadcastWith()
    {
        return [
            'post_id' => $this->post->id,
            'user' => $this->user->only(['id', 'username']),
            'likes_count' => $this->post->likes()->count(),
            'message' => $this->user->username.' liked your post',
            'type' => 'post_liked'
        ];
    }

    public function broadcastAs()
    {
        return 'post.liked';
    }
}
