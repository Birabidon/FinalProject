<script setup>
import {defineProps, defineEmits, ref, onUnmounted} from 'vue';
import {Link} from "@inertiajs/vue3";
import Avatar from "@/Components/Avatar.vue";
import {BIconCalendar, BIconGeoAlt} from "bootstrap-icons-vue";
import StarsScale from "@/Components/StarsScale.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
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
                            <UserAvatar
                                 :user="post.user"
                                 size="3.5rem"
                            />
                            <StarsScale
                                :averageRating="parseInt(post.average_rating)"
                                :votesCount="parseInt(post.votes_count)"
                                :userRating="parseInt(post.user_rating)"
                                :votable="false"
                            />
                        </div>

                        <div class="modal-content">
                            <div class="info-section">
                                <div class="info-item">
                                    <BIconGeoAlt class="info-icon" />
                                    <span>{{ post.location }}</span>
                                </div>
                                <div class="info-item">
                                    <BIconCalendar class="info-icon" />
                                    <span>{{ post.created_at }}</span>
                                </div>
                            </div>

                            <div class="coordinates-section">
                                <strong>Coordinates:</strong> {{ post.lat.toFixed(6) }}, {{ post.lng.toFixed(6) }}
                            </div>

                            <p class="post-title">{{ post.title }}</p>

                            <div class="modal-footer">
                                <button @click="showMore" class="show-more-btn">Show more</button>
                            </div>
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
    right: 350px;
    transition: transform 0.3s linear;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
}

.modal-bookmark.closed {
    right: 0;
}

.bookmark-close {
    transform: translateX(350px);
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

/* New improved styles */
.modal-container {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.post-author {
    font-size: 1.1rem;
    font-weight: 500;
    margin: 0;
    color: #343a40;
}

.modal-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    padding: 16px;
    justify-content: space-between;
}

.info-section {
    margin: 24px 0;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
}

.info-icon {
    margin-right: 12px;
    font-size: 18px;
    color: #0d6efd;
}

.coordinates-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 24px;
    border: 1px solid #e9ecef;
    font-size: 14px;
}

.post-title {
    font-size: 1.5rem;
    color: #343a40;
    margin: 0;
    text-align: center;
}

.modal-footer {
    margin-top: auto;
    padding: 16px 0;
    border-top: 1px solid #dee2e6;
}

.show-more-btn {
    display: block;
    width: 100%;
    background-color: #0d6efd;
    border: none;
    border-radius: 4px;
    color: white;
    padding: 10px 15px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 15px 0 0 0;
    text-align: center;
}

.show-more-btn:hover {
    background-color: #0b5ed7;
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
