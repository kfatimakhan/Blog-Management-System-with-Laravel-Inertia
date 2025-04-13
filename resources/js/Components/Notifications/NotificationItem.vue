<template>
    <div
      class="p-3 border-b hover:bg-gray-50 cursor-pointer"
      :class="{ 'bg-blue-50': !notification.read_at }"
      @click="markAsRead"
    >
      <div class="flex items-start">
        <div class="flex-shrink-0 pt-1">
          <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="notification.type === 'like'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
        </div>
        <div class="ml-3 flex-1">
          <p class="text-sm text-gray-700" v-html="notification.message"></p>
          <p class="text-xs text-gray-500 mt-1">{{ formatDate(notification.created_at) }}</p>
        </div>
        <div v-if="!notification.read_at" class="ml-3 flex-shrink-0">
          <span class="h-2 w-2 rounded-full bg-blue-500 block"></span>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { Inertia } from '@inertiajs/inertia';

  export default {
    props: {
      notification: {
        type: Object,
        required: true
      }
    },
    methods: {
      markAsRead() {
        if (!this.notification.read_at) {
          Inertia.post(`/notifications/${this.notification.id}/mark-as-read`, {}, {
            preserveScroll: true,
            preserveState: false
          });
        }

        // Navigate to the relevant post
        // This would need to be adjusted based on your notification structure
        Inertia.visit(`/posts/${this.notification.data.post_id}`);
      },
      formatDate(dateString) {
        return new Date(dateString).toLocaleString('en-US', {
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      }
    }
  }
  </script>
