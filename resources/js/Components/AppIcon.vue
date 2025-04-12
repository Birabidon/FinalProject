<!-- AppIcon.vue -->
<script setup>
import BootstrapIcons from "bootstrap-icons/bootstrap-icons.svg";

defineProps({
    icon: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
});
</script>

<template>
    <svg class="icon" fill="currentColor">
        <g transform-origin="center">
            <use v-bind:xlink:href="`${BootstrapIcons}#${icon}`" />
        </g>
    </svg>
</template>

<style scoped>
.icon {
    width: 1em;
    height: 1em;
}
</style>
