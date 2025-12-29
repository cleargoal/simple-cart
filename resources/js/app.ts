import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import Toaster from './components/ui/toast/Toaster.vue';
import FlashMessages from './components/FlashMessages.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h('div', [
                h(App, props),
                h(Toaster),
                h(FlashMessages),
            ])
        })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Handle CSRF token errors by reloading the page
document.addEventListener('inertia:error', (event) => {
    // @ts-ignore
    if (event.detail.response?.status === 419) {
        console.warn('CSRF token mismatch. Reloading page to refresh token...');
        window.location.reload();
    }
});

// This will set light / dark mode on page load...
initializeTheme();
