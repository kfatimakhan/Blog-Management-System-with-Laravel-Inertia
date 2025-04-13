<template>
    <div class="min-h-screen bg-gray-50">
      <!-- Navigation -->
      <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <inertia-link href="/" class="text-xl font-bold text-indigo-600">
                  Blog System
                </inertia-link>
              </div>
              <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <inertia-link
                  href="/"
                  class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                  :class="{ 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !$page.url === '/' }"
                >
                  Home
                </inertia-link>
                <inertia-link
                  v-if="$page.props.auth.user"
                  href="/dashboard"
                  class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                  :class="{ 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !$page.url.startsWith('/dashboard') }"
                >
                  Dashboard
                </inertia-link>
              </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
              <!-- Notifications dropdown -->
              <div class="ml-3 relative">
                <inertia-link
                  v-if="$page.props.auth.user"
                  href="/notifications"
                  class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
                >
                  <span class="sr-only">View notifications</span>
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                  </svg>
                  <span
                    v-if="$page.props.auth.user && $page.props.auth.user.unread_notifications_count > 0"
                    class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"
                  ></span>
                </inertia-link>
              </div>

              <!-- Profile dropdown -->
              <div class="ml-3 relative" v-if="$page.props.auth.user">
                <div>
                  <button @click="profileMenuOpen = !profileMenuOpen" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu">
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full" :src="$page.props.auth.user.profile_pic ? `/storage/${$page.props.auth.user.profile_pic}` : '/default-profile.png'" alt="">
                  </button>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <div v-show="profileMenuOpen" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    <inertia-link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</inertia-link>
                    <inertia-link href="/bookmarks" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Bookmarks</inertia-link>
                    <inertia-link href="/logout" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</inertia-link>
                  </div>
                </transition>
              </div>

              <div v-else class="flex space-x-4">
                <inertia-link href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Login</inertia-link>
                <inertia-link href="/register" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Register</inertia-link>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Content -->
      <main>
        <slot />
      </main>

      <!-- Toast Notifications -->
      <toast />
    </div>
  </template>

  <script>
  import { ref } from 'vue';

  export default {
    setup() {
      const profileMenuOpen = ref(false);

      return {
        profileMenuOpen
      };
    }
  }
  </script>
