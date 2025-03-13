<script setup>
const model = defineModel({
    type: null, // null because form values are null
    required: true
})

defineProps({
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: "text"
    },
    message: String
})
</script>

<template>
    <div class="mb-6">
        <label>{{ name }}</label>
        <input :type="type" v-model="model" :class="{'!ring-red-500' : message}">
        <small class="error" v-if="message">{{ message }}</small>
    </div>
</template>

<style scoped>

</style>
