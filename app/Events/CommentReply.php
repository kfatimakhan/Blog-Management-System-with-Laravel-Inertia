<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentReply implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $parentComment;

    public function __construct(Comment $comment, Comment $parentComment)
    {
        $this->comment = $comment;
        $this->parentComment = $parentComment;
    }

    public function broadcastOn()
    {
        return [
            new Channel('post.'.$this->comment->post_id),
            new Channel('user.'.$this->parentComment->user_id)
        ];
    }

    public function broadcastWith()
    {
        return [
            'comment' => $this->comment->load('user'),
            'parent_comment_id' => $this->parentComment->id,
            'type' => 'comment_reply'
        ];
    }
}
