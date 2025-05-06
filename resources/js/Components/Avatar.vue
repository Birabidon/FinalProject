<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(
    {
        path: String,
        alt: String,
        url: Boolean,
    }
);
</script>

<template>
    <img
        :src="path ? (url ? path : ('/storage/' + path)) : ('/storage/avatars/default.jpg')"
        :alt="alt || 'avatar'"
        class="avatar">
</template>

<style>
.avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    overflow: hidden;
    object-position: center;
    object-fit: cover;
    border: 2px solid #000000;
}
</style>
