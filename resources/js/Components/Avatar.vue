<script setup>
import { defineProps } from 'vue';

const props = defineProps(
    {
        avatar: String,
        alt: String,
        url: Boolean
    }
);
</script>

<template>
    <img
        :src="avatar ? (url ? avatar : ('/storage/' + avatar)) : ('/storage/avatars/default.jpg')"
        :alt="alt || 'avatar'"
        class="avatar">
</template>
