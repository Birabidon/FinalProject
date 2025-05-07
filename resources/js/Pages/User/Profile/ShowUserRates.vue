<script setup>
import { ref, watch, defineProps } from 'vue';
import PostCard from "@/Components/Posts/PostCard.vue";
import ProfilePage from '@/Pages/User/Show.vue';

const props = defineProps({
    user: Object,
    posts: Object
});
</script>

<template>
    <div class="posts-container">
        <div
            v-for="post in posts"
            :key="post.id"
        >
            <PostCard
                :post="post"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
