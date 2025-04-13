@extends('layouts.app')

@section('title', isset($post) ? 'Edit Post' : 'Create Post')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">{{ isset($post) ? 'Edit Post' : 'Create New Post' }}</h1>

                <form action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($post))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               required>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <textarea id="content" name="content" rows="6"
                                  class="w-full rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                  required>{{ old('content', $post->content ?? '') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image (Optional)</label>
                        <input type="file" id="image" name="image"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($post) && $post->image)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Current Image:</p>
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Current post image" class="h-32 mt-2 rounded">
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="visibility" value="public"
                                       {{ old('visibility', $post->visibility ?? 'public') === 'public' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2">Public</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="visibility" value="private"
                                       {{ old('visibility', $post->visibility ?? '') === 'private' ? 'checked' : '' }}
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2">Private</span>
                            </label>
                        </div>
                        @error('visibility')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma separated)</label>
                        <input type="text" id="tags" name="tags"
                               value="{{ old('tags', isset($post) ? $post->tags->pluck('name')->join(', ') : '') }}"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               placeholder="e.g. laravel, vue, javascript">
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('posts.index') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-3">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            {{ isset($post) ? 'Update' : 'Create' }} Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
