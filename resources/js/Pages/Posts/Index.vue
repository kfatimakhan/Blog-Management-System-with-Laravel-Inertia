<template>
    <app-layout>
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Blog Posts</h1>

            <!-- Search Form -->
            <div class="mb-6">
              <form @submit.prevent="searchPosts">
                <div class="flex">
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search by title, content, tags, or username"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  >
                  <button
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Search
                  </button>
                </div>
              </form>
            </div>

            <!-- Create Post Button -->
            <div class="mb-6" v-if="$page.props.auth.user">
              <inertia-link
                href="/posts/create"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Create New Post
              </inertia-link>
            </div>

            <!-- Posts List -->
            <div v-if="posts.data.length > 0" class="space-y-6">
              <post-card
                v-for="post in posts.data"
                :key="post.id"
                :post="post"
                @like-toggled="updatePostLikes"
              />
            </div>

            <!-- No Posts Message -->
            <div v-else class="text-center py-8">
              <p class="text-gray-500">No posts found.</p>
            </div>

            <!-- Pagination -->
            <div class="mt-6" v-if="posts.meta.last_page > 1">
              <div class="flex justify-between items-center">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ posts.meta.from }}</span> to
                    <span class="font-medium">{{ posts.meta.to }}</span> of
                    <span class="font-medium">{{ posts.meta.total }}</span> results
                  </p>
                </div>
                <div class="flex space-x-2">
                  <inertia-link
                    v-for="link in posts.meta.links"
                    :key="link.label"
                    :href="link.url"
                    :class="{
                      'px-4 py-2 border rounded-md': true,
                      'bg-indigo-600 text-white border-indigo-600': link.active,
                      'border-gray-300 hover:bg-gray-50': !link.active && link.url,
                      'text-gray-400 cursor-not-allowed': !link.url
                    }"
                    preserve-scroll
                  >
                    <span v-html="link.label"></span>
                  </inertia-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>

  <script>
  import AppLayout from '@/Components/Layouts/AppLayout.vue';
  import PostCard from '@/Components/Posts/PostCard.vue';
  import { ref, watch } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import { debounce } from 'lodash';

  export default {
    components: {
      AppLayout,
      PostCard
    },
    props: {
      posts: Object,
      filters: Object
    },
    setup(props) {
      const searchQuery = ref(props.filters.search || '');

      // Debounced search
      const searchPosts = debounce(() => {
        Inertia.get('/', { search: searchQuery.value }, {
          preserveState: true,
          replace: true
        });
      }, 500);

      // Watch for changes in search query
      watch(searchQuery, () => {
        searchPosts();
      });

      // Update post likes count after toggle
      const updatePostLikes = (postId, likesCount, isLiked) => {
        const postIndex = props.posts.data.findIndex(p => p.id === postId);
        if (postIndex !== -1) {
          props.posts.data[postIndex].likes_count = likesCount;
          props.posts.data[postIndex].is_liked = isLiked;
        }
      };

      return {
        searchQuery,
        searchPosts,
        updatePostLikes
      };
    }
  }
  </script>
