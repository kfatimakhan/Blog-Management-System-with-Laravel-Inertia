<?php

namespace App\Listeners;

use App\Events\NewComment;
use App\Models\Notification;
use App\Events\NotificationSent;

class SendNewCommentNotification
{
    public function handle(NewComment $event)
    {
        $post = $event->comment->post;

        // Don't notify if user is commenting on their own post
        if ($event->comment->user_id === $post->user_id) {
            return;
        }

        $notification = Notification::create([
            'user_id' => $post->user_id,
            'type' => 'comment',
            'message' => $event->comment->user->username.' commented on your post: "'.str_limit($post->title, 50).'"'
        ]);

        // Broadcast the notification
        broadcast(new NotificationSent($notification, $post->user));
    }
}
