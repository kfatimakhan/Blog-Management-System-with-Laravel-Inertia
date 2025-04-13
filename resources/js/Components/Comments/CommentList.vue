<template>
    <div class="space-y-4">
      <div v-for="comment in comments" :key="comment.id" class="border-b pb-4 last:border-b-0 last:pb-0">
        <div class="flex items-start">
          <img :src="comment.user.profile_pic ? `/storage/${comment.user.profile_pic}` : '/default-profile.png'"
               :alt="comment.user.username"
               class="h-8 w-8 rounded-full mr-3">
          <div class="flex-1">
            <div class="bg-gray-50 p-3 rounded-lg">
              <div class="flex justify-between items-start">
                <div>
                  <h4 class="font-medium text-sm">{{ comment.user.username }}</h4>
                  <p class="text-gray-500 text-xs">{{ formatDate(comment.created_at) }}</p>
                </div>
                <button
                  v-if="$page.props.auth.user && ($page.props.auth.user.id === comment.user.id || $page.props.auth.user.id === postOwnerId)"
                  @click="deleteComment(comment.id)"
                  class="text-gray-400 hover:text-red-500 text-sm"
                >
                  Delete
                </button>
              </div>
              <p class="mt-1 text-sm text-gray-700">{{ comment.content }}</p>
            </div>

            <div class="mt-2 ml-4 pl-4 border-l-2 border-gray-100">
              <!-- Reply form -->
              <comment-form
                v-if="showReplyForm === comment.id"
                :post-id="postId"
                :parent-id="comment.id"
                @comment-added="handleNewReply(comment)"
              />

              <!-- Reply button -->
              <button
                v-if="$page.props.auth.user && showReplyForm !== comment.id"
                @click="toggleReplyForm(comment.id)"
                class="text-xs text-gray-500 hover:text-indigo-500 mt-1 flex items-center"
              >
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                Reply
              </button>

              <!-- Nested comments -->
              <comment-list
                v-if="comment.replies && comment.replies.length > 0"
                :comments="comment.replies"
                :post-id="postId"
                :post-owner-id="postOwnerId"
                class="mt-2"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { ref } from 'vue';
  import CommentForm from '@/Components/Comments/CommentForm.vue';
  import { Inertia } from '@inertiajs/inertia';

  export default {
    components: {
      CommentForm
    },
    props: {
      comments: {
        type: Array,
        required: true
      },
      postId: {
        type: Number,
        required: true
      },
      postOwnerId: {
        type: Number,
        required: true
      }
    },
    setup(props) {
      const showReplyForm = ref(null);

      const toggleReplyForm = (commentId) => {
        showReplyForm.value = showReplyForm.value === commentId ? null : commentId;
      };

      const deleteComment = (commentId) => {
        if (confirm('Are you sure you want to delete this comment?')) {
          Inertia.delete(`/comments/${commentId}`, {
            preserveScroll: true
          });
        }
      };

      const handleNewReply = (parentComment) => {
        if (!parentComment.replies) {
          parentComment.replies = [];
        }
        // The actual reply will be added via Pusher real-time update
        showReplyForm.value = null;
      };

      const formatDate = (dateString) => {
        return new Date(dateString).toLocaleString('en-US', {
          month: 'short',
          day: 'numeric',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      };

      return {
        showReplyForm,
        toggleReplyForm,
        deleteComment,
        handleNewReply,
        formatDate
      };
    }
  }
  </script>
