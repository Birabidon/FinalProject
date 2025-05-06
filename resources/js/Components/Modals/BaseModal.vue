<script setup lang="ts">
// import AppIcon from "@/Components/AppIcon.vue";
import {BIconXLg} from "bootstrap-icons-vue";

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// This component is not used anywhere, but mb I will need it later so why not left it here
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

const emit = defineEmits(["close"]);

function closeModal() {
    emit("close");
}
</script>

<template>
    <Teleport to="body">
        <div class="modal" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div class="title" v-if="$slots.title">
                    <slot name="title" />
                </div>
                <BIconXLg @click="closeModal" clickable class="close" />
                <slot name="body"/>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
.modal {
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    background-color: rgba(0, 0, 0, 0.5); /* Replaced $color-backdrop */
}

.modal-content {
    position: relative;
    max-width: 600px;
    width: 100%;
    padding: 16px; /* Replaced spacing(4) */
    margin: 0 12px; /* Replaced spacing(3) */
    background-color: white; /* Replaced $color-white */
    border-radius: 8px; /* Replaced $border-radius */
}

.title {
    font-size: 1.25rem; /* Replaced $font-size-3 */
    font-weight: 700;
    margin-bottom: 8px; /* Replaced spacing(2) */
}

.close {
    position: absolute;
    top: 12px; /* Replaced spacing(3) */
    right: 12px; /* Replaced spacing(3) */
    font-size: 1.5rem; /* Adjust this value to change size */
    cursor: pointer;
}
</style>
