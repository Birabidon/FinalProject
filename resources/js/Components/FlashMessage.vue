<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    flash: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

onMounted(() => {
    setTimeout(close, 5000); // Auto close after 5 seconds
});
</script>

<template>
    <div :class="['flash-message', flash.type]">
        <span>{{ flash.message }}</span>
        <button @click="close">X</button>
    </div>
</template>

<style scoped>
.flash-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: opacity 0.3s ease;
    z-index: 1000;
    background-color: #0056b3;
}

.success {
    background-color: #d4edda;
    color: #155724;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
}

.flash-message button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}
</style>
