<?php

namespace App\Providers;

use App\Events\NewComment;
use App\Events\PostLiked;
use App\Events\CommentReply;
use App\Events\NotificationSent;
use App\Listeners\SendNewCommentNotification;
use App\Listeners\SendPostLikedNotification;
use App\Listeners\SendCommentReplyNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewComment::class => [
            SendNewCommentNotification::class,
        ],
        PostLiked::class => [
            SendPostLikedNotification::class,
        ],
        CommentReply::class => [
            SendCommentReplyNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
