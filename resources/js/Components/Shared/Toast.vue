<template>
    <transition-group
      name="toast"
      tag="div"
      class="fixed bottom-4 right-4 space-y-3 w-80 z-50"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="p-4 rounded-md shadow-lg text-white"
        :class="{
          'bg-green-500': toast.type === 'success',
          'bg-red-500': toast.type === 'error',
          'bg-blue-500': toast.type === 'info'
        }"
      >
        <div class="flex justify-between items-start">
          <p class="text-sm font-medium">{{ toast.message }}</p>
          <button @click="removeToast(toast.id)" class="ml-4 text-white hover:text-gray-200">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </transition-group>
  </template>

  <script>
  import { computed } from 'vue';
  import { usePage } from '@inertiajs/inertia-vue3';

  export default {
    setup() {
      const toasts = computed(() => usePage().props.value.toasts || []);

      const removeToast = (id) => {
        // This would need to be connected to your backend to persist dismissal
        // For now, we'll just remove it from the frontend
        const index = toasts.value.findIndex(t => t.id === id);
        if (index !== -1) {
          toasts.value.splice(index, 1);
        }
      };

      return {
        toasts,
        removeToast
      };
    }
  }
  </script>

  <style>
  .toast-enter-active,
  .toast-leave-active {
    transition: all 0.3s ease;
  }

  .toast-enter-from,
  .toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
  }

  .toast-move {
    transition: transform 0.3s ease;
  }
  </style>
