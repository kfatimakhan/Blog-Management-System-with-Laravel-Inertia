@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-2">{{ $post->title }}</h1>

                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <span>Posted by {{ $post->user->username }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </div>

                <div class="prose max-w-none mb-6">
                    {!! nl2br(e($post->content)) !!}
                </div>

                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="mb-6 rounded-lg w-full">
                @endif

                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($post->tags as $tag)
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Action Buttons -->
                @auth
                    @if(auth()->id() === $post->user_id)
                        <div class="flex space-x-4 mt-6">
                            <a href="{{ route('posts.edit', $post) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Edit
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
