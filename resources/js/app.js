import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';


//saving dark mode after refreshing browser using locaStorage
const darkClass = localStorage.getItem('darkMode');

if(darkClass){
    document.querySelector('html').classList.add(darkClass);
}

//////////////////////////////////////////////////////////////

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// "@ckeditor/ckeditor5-build-balloon": "^41.4.2",
// "@ckeditor/ckeditor5-vue": "^5.1.0",
//"ckeditor5": "^45.0.0"
