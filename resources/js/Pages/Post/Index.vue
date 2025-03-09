<script setup>
import { ref, defineProps } from 'vue'

const props = defineProps({
    posts: {
        type: Array,
        // required: true
    }
})

</script>

<template>
    <div>
        <h2>Posts</h2>
        <ul>
            <li v-for="post in posts" :key="post.id">
                <a :href="`/posts/${post.id}`">{{ post.title }}</a>
            </li>
        </ul>
    </div>
</template>

<style scoped>

</style>
