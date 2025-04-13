import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Import plugins
import Toast from '@/Components/Shared/Toast.vue';
import Pusher from 'pusher-js';

// Create the Inertia app
createInertiaApp({
    title: (title) => `${title} - Blog Management System`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        // Register global components
        vueApp.component('Toast', Toast);

        // Mount the app
        vueApp.mount(el);

        // Initialize Pusher for real-time features
        if (import.meta.env.VITE_PUSHER_APP_KEY) {
            window.Pusher = Pusher;
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
                encrypted: true,
            });
        }
    },
});

// Initialize progress bar
InertiaProgress.init({
    color: '#4f46e5',
    showSpinner: true,
});
