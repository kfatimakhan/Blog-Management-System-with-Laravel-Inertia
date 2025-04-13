<template>
    <app-layout>
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

              <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <!-- Profile Picture -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                    <div class="flex items-center">
                      <img
                        :src="form.profile_pic_preview || ($page.props.auth.user.profile_pic ? `/storage/${$page.props.auth.user.profile_pic}` : '/default-profile.png')"
                        alt="Current profile picture"
                        class="h-16 w-16 rounded-full object-cover mr-4"
                      >
                      <input
                        type="file"
                        @change="handleProfilePicChange"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                      >
                      <p v-if="errors.profile_pic" class="mt-1 text-sm text-red-600">{{ errors.profile_pic }}</p>
                    </div>
                  </div>

                  <!-- Username -->
                  <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input
                      id="username"
                      v-model="form.username"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      required
                    >
                    <p v-if="errors.username" class="mt-1 text-sm text-red-600">{{ errors.username }}</p>
                  </div>

                  <!-- Email -->
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                      id="email"
                      v-model="form.email"
                      type="email"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      required
                    >
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                  </div>

                  <!-- Current Password -->
                  <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password (for verification)</label>
                    <input
                      id="current_password"
                      v-model="form.current_password"
                      type="password"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <p v-if="errors.current_password" class="mt-1 text-sm text-red-600">{{ errors.current_password }}</p>
                  </div>

                  <!-- New Password -->
                  <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password (leave blank to keep current)</label>
                    <input
                      id="new_password"
                      v-model="form.new_password"
                      type="password"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    <p v-if="errors.new_password" class="mt-1 text-sm text-red-600">{{ errors.new_password }}</p>
                  </div>

                  <!-- Confirm New Password -->
                  <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input
                      id="new_password_confirmation"
                      v-model="form.new_password_confirmation"
                      type="password"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                  </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                  <inertia-link
                    href="/profile"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Cancel
                  </inertia-link>
                  <button
                    type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="form.processing"
                  >
                    Save Changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>

  <script>
  import AppLayout from '@/Components/Layouts/AppLayout.vue';
  import { useForm } from '@inertiajs/inertia-vue3';

  export default {
    components: {
      AppLayout
    },
    props: {
      errors: Object
    },
    setup() {
      const form = useForm({
        username: usePage().props.value.auth.user.username,
        email: usePage().props.value.auth.user.email,
        profile_pic: null,
        profile_pic_preview: null,
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      });

      const handleProfilePicChange = (e) => {
        const file = e.target.files[0];
        if (file) {
          form.profile_pic = file;
          const reader = new FileReader();
          reader.onload = (e) => {
            form.profile_pic_preview = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      };

      const submit = () => {
        form.post('/profile', {
          preserveScroll: true
        });
      };

      return {
        form,
        handleProfilePicChange,
        submit
      };
    }
  }
  </script>
