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
    placeholder: {
        type: String,
        default: "Enter text"
    },
    message: String
})
</script>

<template>
    <div class="mb-6">
        <label>{{ name }}</label>
        <input :type="type" v-model="model" :class="{'red' : message}" :placeholder="placeholder">
        <small class="error" v-if="message">{{ message }}</small>
    </div>
</template>

<style scoped>
.red {
    color: rgb(239, 68, 68);
}
</style>
