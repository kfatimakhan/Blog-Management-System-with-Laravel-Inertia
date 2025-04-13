<template>
    <AppLayout>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Welcome Section -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
              <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Our Blog</h1>
              <p class="text-gray-600 mb-4">Discover the latest articles and insights from our community.</p>

              <div v-if="$page.props.auth.user" class="flex space-x-4">
                <Link
                  :href="route('posts.create')"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                  Create New Post
                </Link>
              </div>
              <div v-else class="flex space-x-4">
                <Link
                  :href="route('login')"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                  Login to Contribute
                </Link>
                <Link
                  :href="route('register')"
                  class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50"
                >
                  Register
                </Link>
              </div>
            </div>
          </div>

          <!-- Featured Posts -->
          <div v-if="featuredPosts.length > 0" class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Featured Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <PostCard
                v-for="post in featuredPosts"
                :key="post.id"
                :post="post"
                class="hover:shadow-lg transition-shadow duration-300"
              />
            </div>
          </div>

          <!-- Latest Posts -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Latest Posts</h2>
                <div class="relative w-64">
                  <input
                    v-model="search"
                    type="text"
                    placeholder="Search posts..."
                    class="w-full pl-4 pr-10 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @keyup.enter="searchPosts"
                  >
                  <button
                    @click="searchPosts"
                    class="absolute right-2 top-2 text-gray-400 hover:text-indigo-600"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </button>
                </div>
              </div>

              <div v-if="posts.data.length > 0">
                <div class="space-y-6">
                  <PostCard
                    v-for="post in posts.data"
                    :key="post.id"
                    :post="post"
                  />
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                  <Pagination :links="posts.meta.links" />
                </div>
              </div>
              <div v-else class="text-center py-8">
                <p class="text-gray-500">No posts found.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, watch } from 'vue';
  import { Link, router } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import PostCard from '@/Components/PostCard.vue';
  import Pagination from '@/Components/Pagination.vue';

  const props = defineProps({
    featuredPosts: {
      type: Array,
      default: () => []
    },
    posts: {
      type: Object,
      required: true
    },
    filters: {
      type: Object,
      default: () => ({})
    }
  });

  const search = ref(props.filters.search || '');

  // Debounce search
  let searchTimeout = null;
  watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      searchPosts();
    }, 500);
  });

  const searchPosts = () => {
    router.get(route('home'), { search: search.value }, {
      preserveState: true,
      replace: true
    });
  };
  </script>

  <style scoped>
  
  </style>
