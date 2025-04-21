<script setup>
import AppLayout from '@/Layouts/App.vue';
import GuestLayout from '@/Layouts/Guest.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

import { ref, watch, onMounted } from "vue";
import {usePage} from "@inertiajs/vue3";

let showMessage = ref(false)

onMounted(() => { // this will work if the page is loaded using url and not using inertia
    if (usePage().props.flash.message) {
        console.log('FlashMessage', usePage().props.flash.message);
        showMessage.value = true;
    }
});

watch(() => usePage().props.flash, (newFlash) => { // im checking .flash cuz if I check flash.message if message is the same it won't work
    if (newFlash.message) {
        console.log('newFlash', newFlash);
        showMessage.value = !showMessage.value;
    }
});

const onClose = () => {
    showMessage.value = !showMessage.value;
    console.log('showMessage', showMessage.value);
}
</script>

<template>
    <FlashMessage v-if="showMessage" :flash="$page.props.flash" @close="onClose"/>
    <component :is="$page.props.auth.user ? AppLayout : GuestLayout">
        <slot />
    </component>
</template>
