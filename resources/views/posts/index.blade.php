@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">All Posts</h1>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search Form -->
                <form action="{{ route('posts.index') }}" method="GET" class="mb-6">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Search posts..."
                               value="{{ request('search') }}"
                               class="rounded-l-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </form>

                <!-- Posts List -->
                @forelse($posts as $post)
                    <div class="mb-8 pb-6 border-b border-gray-200 last:border-b-0">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 200) }}</p>

                        <div class="flex items-center text-sm text-gray-500">
                            <span>Posted by {{ $post->user->username }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No posts found.</p>
                @endforelse

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
