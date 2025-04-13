<template>
    <app-layout>
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <post-form />
          </div>
        </div>
      </div>
    </app-layout>
  </template>

  <script>
  import AppLayout from '@/Components/Layouts/AppLayout.vue';
  import PostForm from '@/Components/Posts/PostForm.vue';

  export default {
    components: {
      AppLayout,
      PostForm
    }
  }
  </script>
