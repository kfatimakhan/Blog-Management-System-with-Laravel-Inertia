<template>
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
      <!-- Post Header -->
      <div class="p-4 flex items-center border-b">
        <inertia-link :href="`/profile/${post.user.username}`" class="flex items-center">
          <img :src="post.user.profile_pic ? `/storage/${post.user.profile_pic}` : '/default-profile.png'"
               :alt="post.user.username"
               class="h-10 w-10 rounded-full object-cover">
          <div class="ml-3">
            <h3 class="font-medium text-gray-900">{{ post.user.username }}</h3>
            <p class="text-xs text-gray-500">{{ formatDate(post.created_at) }}</p>
          </div>
        </inertia-link>
        <div class="ml-auto" v-if="$page.props.auth.user && $page.props.auth.user.id === post.user.id">
          <inertia-link :href="`/posts/${post.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">
            Edit
          </inertia-link>
          <inertia-link :href="`/posts/${post.id}`" method="delete" as="button" class="text-red-600 hover:text-red-900">
            Delete
          </inertia-link>
        </div>
      </div>

      <!-- Post Content -->
      <div class="p-4">
        <inertia-link :href="`/posts/${post.id}`" class="block">
          <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ post.title }}</h2>
          <p class="text-gray-600 mb-4">{{ post.content }}</p>
          <img v-if="post.image" :src="`/storage/${post.image}`" alt="Post image" class="w-full h-auto rounded-lg mb-4">
        </inertia-link>

        <!-- Tags -->
        <div class="flex flex-wrap gap-2 mb-4">
          <inertia-link
            v-for="tag in post.tags"
            :key="tag.id"
            :href="`/?search=${tag.name}`"
            class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full hover:bg-gray-200"
          >
            #{{ tag.name }}
          </inertia-link>
        </div>

        <!-- Post Actions -->
        <div class="flex items-center justify-between border-t pt-3">
          <div class="flex space-x-4">
            <button @click="toggleLike" class="flex items-center text-gray-500 hover:text-red-500" :class="{ 'text-red-500': isLiked }">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span class="ml-1">{{ post.likes_count }}</span>
            </button>
            <button @click="toggleComments" class="flex items-center text-gray-500 hover:text-indigo-500">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <span class="ml-1">{{ post.comments_count }}</span>
            </button>
          </div>
          <button @click="toggleBookmark" class="text-gray-500 hover:text-indigo-500" :class="{ 'text-indigo-500': isBookmarked }">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
          </button>
        </div>

        <!-- Comments Section -->
        <div v-if="showComments" class="mt-4 pt-4 border-t">
          <comment-form :post-id="post.id" @comment-added="handleNewComment" />
          <comment-list :comments="post.comments" />
        </div>
      </div>
    </div>
  </template>

  <script>
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/inertia-vue3';
  import CommentForm from '@/Components/Comments/CommentForm.vue';
  import CommentList from '@/Components/Comments/CommentList.vue';

  export default {
    components: {
      CommentForm,
      CommentList
    },
    props: {
      post: {
        type: Object,
        required: true
      }
    },
    setup(props) {
      const showComments = ref(false);
      const currentUser = computed(() => usePage().props.value.auth.user);
      const isLiked = computed(() => props.post.likes.some(like => like.user_id === currentUser.value?.id));
      const isBookmarked = computed(() => props.post.bookmarks.some(b => b.user_id === currentUser.value?.id));

      const toggleLike = async () => {
        if (!currentUser.value) return;

        try {
          const response = await axios.post(`/posts/${props.post.id}/toggle-like`);
          props.post.likes_count = response.data.likes_count;
          props.post.is_liked = response.data.is_liked;
        } catch (error) {
          console.error(error);
        }
      };

      const toggleBookmark = async () => {
        if (!currentUser.value) return;

        try {
          const response = await axios.post(`/posts/${props.post.id}/toggle-bookmark`);
          props.post.is_bookmarked = response.data.is_bookmarked;
        } catch (error) {
          console.error(error);
        }
      };

      const toggleComments = () => {
        showComments.value = !showComments.value;
      };

      const handleNewComment = (comment) => {
        props.post.comments.unshift(comment);
        props.post.comments_count++;
      };

      const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      };

      return {
        showComments,
        isLiked,
        isBookmarked,
        toggleLike,
        toggleBookmark,
        toggleComments,
        handleNewComment,
        formatDate
      };
    }
  }
  </script>
