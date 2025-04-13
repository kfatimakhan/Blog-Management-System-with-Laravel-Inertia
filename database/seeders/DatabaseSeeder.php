<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 10 users
        $users = User::factory(10)->create();

        // Create 5 tags
        $tags = Tag::factory(5)->create();

        // Each user creates 3-5 posts
        $users->each(function ($user) use ($tags) {
            $posts = Post::factory(rand(3, 5))->create([
                'user_id' => $user->id,
                'visibility' => rand(0, 1) ? 'public' : 'private'
            ]);

            // Attach 1-3 tags to each post
            $posts->each(function ($post) use ($tags) {
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
        });

        // Each post gets 2-5 comments from random users
        Post::all()->each(function ($post) use ($users) {
            $comments = Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
                'user_id' => $users->random()->id
            ]);

            // Some comments get replies
            $comments->random(rand(0, 2))->each(function ($comment) use ($users) {
                Comment::factory(rand(1, 2))->create([
                    'post_id' => $comment->post_id,
                    'user_id' => $users->random()->id,
                    'parent_id' => $comment->id
                ]);
            });
        });

        // Users like random posts
        $users->each(function ($user) {
            $posts = Post::inRandomOrder()->limit(rand(5, 10))->get();
            $posts->each(function ($post) use ($user) {
                if (rand(0, 1)) {
                    Like::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id
                    ]);
                }
            });
        });

        // Users bookmark random posts
        $users->each(function ($user) {
            $posts = Post::inRandomOrder()->limit(rand(3, 6))->get();
            $posts->each(function ($post) use ($user) {
                if (rand(0, 1)) {
                    Bookmark::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id
                    ]);
                }
            });
        });

        // Create some notifications
        $users->each(function ($user) {
            Notification::factory(rand(3, 8))->create([
                'user_id' => $user->id,
                'type' => rand(0, 1) ? 'like' : 'comment',
                'read_at' => rand(0, 1) ? now() : null
            ]);
        });
    }
}
