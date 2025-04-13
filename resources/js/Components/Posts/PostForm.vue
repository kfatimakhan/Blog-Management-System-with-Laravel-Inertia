<template>
    <div class="bg-white shadow rounded-lg overflow-hidden p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ editMode ? 'Edit Post' : 'Create New Post' }}</h2>
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
          <input
            type="text"
            id="title"
            v-model="form.title"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            required
          >
          <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
        </div>

        <div class="mb-4">
          <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
          <textarea
            id="content"
            v-model="form.content"
            rows="5"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            required
          ></textarea>
          <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content }}</p>
        </div>

        <div class="mb-4">
          <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image (Optional)</label>
          <input
            type="file"
            id="image"
            @change="handleImageChange"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          >
          <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
          <img v-if="form.imagePreview" :src="form.imagePreview" alt="Preview" class="mt-2 h-32 object-cover rounded">
          <img v-else-if="post?.image" :src="`/storage/${post.image}`" alt="Current image" class="mt-2 h-32 object-cover rounded">
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
          <div class="flex space-x-4">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.visibility"
                value="public"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
              >
              <span class="ml-2 text-sm text-gray-700">Public</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.visibility"
                value="private"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
              >
              <span class="ml-2 text-sm text-gray-700">Private</span>
            </label>
          </div>
        </div>

        <div class="mb-4">
          <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (Separate with commas)</label>
          <input
            type="text"
            id="tags"
            v-model="form.tagsInput"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="e.g., laravel, vue, javascript"
          >
        </div>

        <div class="flex justify-end space-x-3">
          <inertia-link
            :href="editMode ? `/posts/${post.id}` : '/dashboard'"
            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </inertia-link>
          <button
            type="submit"
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="processing"
          >
            {{ editMode ? 'Update' : 'Create' }} Post
          </button>
        </div>
      </form>
    </div>
  </template>

  <script>
  import { ref, reactive, computed, watch } from 'vue';
  import { useForm, usePage } from '@inertiajs/inertia-vue3';

  export default {
    props: {
      post: {
        type: Object,
        default: null
      },
      errors: {
        type: Object,
        default: () => ({})
      }
    },
    setup(props) {
      const editMode = computed(() => !!props.post);
      const processing = ref(false);

      const form = useForm({
        title: props.post?.title || '',
        content: props.post?.content || '',
        image: null,
        imagePreview: null,
        visibility: props.post?.visibility || 'public',
        tagsInput: props.post?.tags.map(t => t.name).join(', ') || ''
      });

      const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
          form.image = file;
          const reader = new FileReader();
          reader.onload = (e) => {
            form.imagePreview = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      };

      const submitForm = () => {
        processing.value = true;

        const tags = form.tagsInput
          ? form.tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0)
          : [];

        const data = {
          title: form.title,
          content: form.content,
          visibility: form.visibility,
          tags: tags
        };

        if (form.image) {
          data.image = form.image;
        }

        if (editMode.value) {
          form.transform((data) => ({
            ...data,
            _method: 'PUT'
          })).post(`/posts/${props.post.id}`, {
            onFinish: () => {
              processing.value = false;
            }
          });
        } else {
          form.transform((data) => ({
            ...data
          })).post('/posts', {
            onFinish: () => {
              processing.value = false;
            }
          });
        }
      };

      return {
        form,
        editMode,
        processing,
        handleImageChange,
        submitForm
      };
    }
  }
  </script>
