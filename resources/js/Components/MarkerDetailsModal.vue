<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true
    },
    marker: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <transition name="slide">
        <div v-if="isOpen" class="modal-container">
            <div class="modal-header">
                <h3>Marker Details</h3>
                <button class="close-btn" @click="closeModal">&times;</button>
            </div>
            <div class="modal-content" v-if="marker">
                <h4>{{ marker.title }}</h4>
                <p v-if="marker.LocationName"><strong>Location:</strong> {{ marker.LocationName }}</p>
                <p><strong>Coordinates:</strong> {{ marker.lat.toFixed(6) }}, {{ marker.lng.toFixed(6) }}</p>

                <div v-if="marker.content" class="marker-content">
                    {{ marker.content }}
                </div>
            </div>
            <div v-else class="modal-content">
                <p>No marker details available</p>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.modal-container {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width: 350px;
    background: white;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
    z-index: 100;
    overflow: hidden;
    pointer-events: auto;
}


.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.modal-content {
    padding: 20px;
}

.marker-content {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

/* Transition animations */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
</style>
