<template>
    <app-layout>
      <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <post-card :post="post" />
          </div>

          <!-- Related Posts -->
          <div v-if="relatedPosts.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Related Posts</h3>
            <div class="space-y-4">
              <div v-for="relatedPost in relatedPosts" :key="relatedPost.id" class="border-b pb-4 last:border-b-0">
                <inertia-link :href="`/posts/${relatedPost.id}`" class="block">
                  <h4 class="text-md font-medium text-indigo-600 hover:text-indigo-800">{{ relatedPost.title }}</h4>
                  <p class="text-sm text-gray-500 mt-1">by {{ relatedPost.user.username }}</p>
                </inertia-link>
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

  export default {
    components: {
      AppLayout,
      PostCard
    },
    props: {
      post: Object,
      relatedPosts: Array
    }
  }
  </script>
