<template>
    <div class="mb-4">
      <form @submit.prevent="submitComment">
        <div class="flex items-start">
          <img :src="$page.props.auth.user.profile_pic ? `/storage/${$page.props.auth.user.profile_pic}` : '/default-profile.png'"
               :alt="$page.props.auth.user.username"
               class="h-8 w-8 rounded-full mr-3 mt-1">
          <div class="flex-1">
            <textarea
              v-model="form.content"
              rows="2"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Write a comment..."
              required
            ></textarea>
            <div class="flex justify-end mt-2">
              <button
                type="submit"
                class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :disabled="form.processing"
              >
                Post
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </template>

  <script>
  import { reactive } from 'vue';
  import { useForm } from '@inertiajs/inertia-vue3';

  export default {
    props: {
      postId: {
        type: Number,
        required: true
      },
      parentId: {
        type: Number,
        default: null
      }
    },
    emits: ['comment-added'],
    setup(props, { emit }) {
      const form = useForm({
        content: '',
        post_id: props.postId,
        parent_id: props.parentId
      });

      const submitComment = () => {
        form.post(`/posts/${props.postId}/comments`, {
          preserveScroll: true,
          onSuccess: () => {
            form.reset();
            emit('comment-added', {
              id: Math.random(), // Temporary ID until real one comes from server
              user: usePage().props.value.auth.user,
              content: form.content,
              created_at: new Date().toISOString(),
              replies: []
            });
          }
        });
      };

      return {
        form,
        submitComment
      };
    }
  }
  </script>
