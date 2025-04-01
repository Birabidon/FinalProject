<script setup>
import AppLayout from '@/Layouts/App.vue';
import GuestLayout from '@/Layouts/Guest.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

import { ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";

let showMessage = ref(false)

watch(() => usePage().props.flash, (newFlash) => {
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
