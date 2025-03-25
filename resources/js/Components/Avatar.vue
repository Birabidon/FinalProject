<script setup>
import { defineProps } from 'vue';

const props = defineProps(
    {
        user: Object,
    }
);
</script>
<template>
    <img
        :src="user?.avatar ? ('/storage/' + user.avatar) : ('/storage/avatars/default.jpg')"
        :alt="user?.name || 'avatar'"
        class="avatar">
</template>

<style scoped>

</style>
