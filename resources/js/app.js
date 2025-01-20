import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderApp } from '@inertiaui/modal-vue'
import { Head } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`] // means it will auto import Vue files, so you don't have to write full path
    },
    title: title => `${title} | MyProject`,
    setup({ el, App, props, plugin }) {
        createApp({ render: renderApp(App, props) })
            .component('Head', Head)
            .use(plugin)
            .mount(el)
    },
})
