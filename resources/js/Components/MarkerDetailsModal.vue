<script setup>
import {defineProps, defineEmits, ref, onUnmounted} from 'vue';

const props = defineProps({
    marker: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['showMore']);

const isOpen = ref(true);

const toggleModal = () => {
    isOpen.value = !isOpen.value;
};

const showMore = () => {
    emit('showMore');
}
</script>

<template>
    <div class="modal-wrapper">
        <div v-if="!isOpen"
            class="modal-bookmark closed"
            @click="toggleModal"
        >
            <i class="pi pi-info-circle bookmark-icon"></i>
        </div>
        <transition name="slide" appear>
            <div v-if="isOpen">
                <!-- Bookmark/tab that's always visible -->
                <div
                    class="modal-bookmark"
                    :class="{ 'bookmark-close': !isOpen }"
                    @click="toggleModal"
                >
                    <i class="pi pi-times close-icon"></i>
                </div>

                <!-- Modal content -->
                <div class="modal-content-container">
                    <div class="modal-container">
                        <div class="modal-header">
                            <h3>About post</h3>
    <!--                        <button class="close-btn" @click="closeModal">&times;</button>-->
                        </div>

                        <div class="modal-content" v-if="marker">
                            <h4>{{ marker.title }}</h4>
                            <p v-if="marker.LocationName"><strong>Location:</strong> {{ marker.LocationName }}</p>
                            <p><strong>Coordinates:</strong> {{ marker.lat.toFixed(6) }}, {{ marker.lng.toFixed(6) }}</p>

                            <div v-if="marker.content" class="marker-content">
                                {{ marker.content.substring(0, 200) + '...' }}
                                <button @click="showMore" class="show-more-btn">Show more</button>
                            </div>
                            <div v-else class="marker-content">
                                No content
                            </div>
                        </div>
                        <div v-else class="modal-content">
                            <p>No marker details available</p>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.modal-wrapper {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    z-index: 100;
    display: flex;
    width: 350px;
}

.modal-bookmark {
    height: 100px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
    cursor: pointer;
    position: absolute;
    right: 350px; /* Position bookmark to left of modal */
    transition: transform 0.3s linear; /* Match modal transition timing */
}

.modal-bookmark.closed {
    right: 0;
}

/* Move bookmark along with modal when closed */
.bookmark-close {
    transform: translateX(350px); /* Should match modal width */
}

.bookmark-icon, .close-icon {
    font-size: 18px;
}

.bookmark-icon {
    color: #0d6efd;
}

.close-icon {
    color: #6c757d;
}

.modal-content-container {
    width: 350px;
    height: 100%;
    overflow-y: auto;
    background: white;
    box-shadow: -3px 0 10px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
}

.modal-content {
    padding: 20px;
    flex-grow: 1;
}

.marker-content {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.show-more-btn {
    display: block;
    width: 100%;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    color: #0d6efd;
    padding: 8px 15px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 15px 0 0 0;
    text-align: center;
}

.show-more-btn:hover {
    background-color: #e9ecef;
    border-color: #ced4da;
}

/* Transition animations */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s linear;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

</style>
