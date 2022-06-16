import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, usePage } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
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
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });