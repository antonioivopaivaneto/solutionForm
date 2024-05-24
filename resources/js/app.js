import './bootstrap';
import '../css/app.css';
import 'vue3-simple-typeahead/dist/vue3-simple-typeahead.css'; //Optional default CSS

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import SimpleTypeahead from 'vue3-simple-typeahead';
import VueTheMask from 'vue-the-mask';


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(SimpleTypeahead)
            .use(plugin)
            .use(VueTheMask)
            .use(ZiggyVue, Ziggy)
            .mount(el);

    },
    progress: {
        color: '#4B5563',
    },
});
