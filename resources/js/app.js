import './bootstrap';
import '../css/app.css';
import 'vue3-easy-data-table/dist/style.css';

import { createApp, h } from 'vue';
import { createInertiaApp, usePage } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Inertia } from '@inertiajs/inertia';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

Inertia.on('success', (event) => {
    let isAuthenticated = event.detail.page.props.user !== null;
    window.localStorage.setItem('isAuthenticated', isAuthenticated);
});

window.addEventListener('popstate', (event) => {
    if (window.localStorage.getItem('isAuthenticated') === 'false') {
        event.stopImmediatePropagation();
        Inertia.get('/login');
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const VueApp = createApp({ render: () => h(app, props) });

        // Global property is (for check auth roles)
        VueApp.config.globalProperties.is = function(value) {
            let roles = usePage().props.value.user.roles
            let _return = false
            if(!Array.isArray(roles)) {
                return false
            }
            if(value.includes('|')) {
                value.split('|').forEach(function (item) {
                    if(roles.includes(item.trim())) {
                        _return = true
                    }
                })
            } else if(value.includes('&')) {
                _return = true
                value.split('&').forEach(function (item) {
                    if(!roles.includes(item.trim())) {
                        _return = false
                    }
                })
            } else {
                _return = roles.includes(value.trim())
            }
            return _return
        }

        VueApp.use(plugin)
            .use(ZiggyVue, Ziggy)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });