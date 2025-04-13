<template>
    <app-layout>
      <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Create a new account
          </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
          <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" @submit.prevent="submit">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-700">
                  Username
                </label>
                <div class="mt-1">
                  <input
                    id="username"
                    v-model="form.username"
                    type="text"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p v-if="errors.username" class="mt-1 text-sm text-red-600">{{ errors.username }}</p>
                </div>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                  Email address
                </label>
                <div class="mt-1">
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                </div>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                  Password
                </label>
                <div class="mt-1">
                  <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
                </div>
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                  Confirm Password
                </label>
                <div class="mt-1">
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                </div>
              </div>

              <div>
                <label for="profile_pic" class="block text-sm font-medium text-gray-700">
                  Profile Picture (Optional)
                </label>
                <div class="mt-1 flex items-center">
                  <img
                    v-if="form.profile_pic_preview"
                    :src="form.profile_pic_preview"
                    class="h-12 w-12 rounded-full object-cover mr-3"
                  >
                  <input
                    id="profile_pic"
                    type="file"
                    @change="handleProfilePicChange"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p v-if="errors.profile_pic" class="mt-1 text-sm text-red-600">{{ errors.profile_pic }}</p>
                </div>
              </div>

              <div>
                <button
                  type="submit"
                  class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :disabled="form.processing"
                >
                  Register
                </button>
              </div>
            </form>

            <div class="mt-6">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                  <span class="px-2 bg-white text-gray-500">
                    Already have an account?
                  </span>
                </div>
              </div>

              <div class="mt-6">
                <p class="text-center text-sm text-gray-600">
                  <inertia-link href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Sign in
                  </inertia-link>
                </p>
              </div>
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
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        profile_pic: null,
        profile_pic_preview: null
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
        form.post('/register');
      };

      return {
        form,
        handleProfilePicChange,
        submit
      };
    }
  }
  </script>
