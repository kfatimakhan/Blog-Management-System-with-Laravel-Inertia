<template>
    <app-layout>
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <!-- Profile Header -->
              <div class="flex items-center mb-8">
                <img
                  :src="user.profile_pic ? `/storage/${user.profile_pic}` : '/default-profile.png'"
                  :alt="user.username"
                  class="h-20 w-20 rounded-full object-cover mr-6"
                >
                <div>
                  <h1 class="text-2xl font-bold text-gray-900">{{ user.username }}</h1>
                  <p class="text-gray-600">{{ user.posts_count }} posts</p>
                </div>
                <div class="ml-auto" v-if="$page.props.auth.user && $page.props.auth.user.id === user.id">
                  <inertia-link
                    href="/profile/edit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Edit Profile
                  </inertia-link>
                </div>
              </div>

              <!-- User Posts -->
              <div v-if="posts.data.length > 0">
                <h2 class="text-xl font-semibold mb-4">Posts</h2>
                <div class="space-y-6">
                  <post-card
                    v-for="post in posts.data"
                    :key="post.id"
                    :post="post"
                  />
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

              <!-- No Posts Message -->
              <div v-else class="text-center py-8">
                <p class="text-gray-500">No posts yet.</p>
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
      user: Object,
      posts: Object
    }
  }
  </script>
