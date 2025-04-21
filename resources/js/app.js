import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import LayoutManager from './Layouts/LayoutManager.vue'
import PrimeVue from 'primevue/config';
import 'primeicons/primeicons.css'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`] // means it will auto import Vue files, so you don't have to write full path
        page.default.layout = page.default.layout || LayoutManager // set the page layout to Layout if a layout has not already been set for that page.
        return page
    },
    title: title => `${title} | MyProject`,
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue)
            .component('Head', Head)
            .mount(el)
    },
    progress: { // progress bar options (when page loads slow)
        color: '#4B5563',
        includeCSS: true,
        showSpinner: true,
    }
})
