<script setup>
import { ref, onMounted } from 'vue'
import { BIconGeoAlt, BIconCalendar, BIconPerson } from 'bootstrap-icons-vue'
import Avatar from "@/Components/Avatar.vue";
import GoogleMapComponent from "@/Components/GoogleMapOneMarkerComponent.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
    }
});

const showFullContent = ref(false);
const contentPreview = ref('');

onMounted(() => {
    if (props.post.content && props.post.content.length > 200) {
        contentPreview.value = props.post.content.substring(0, 200) + '...';
    } else {
        contentPreview.value = props.post.content;
        showFullContent.value = true;
    }
});

const toggleContent = () => {
    showFullContent.value = !showFullContent.value;
};

</script>

<template>
    <div class="post-container">
        <div class="post-header">
            <div class="post-user">
                <Avatar :avatar="post.user.avatar" :alt="post.user.name" />
                <h2 class="post-author">{{ post.user.name }}</h2>
            </div>
            <h1 class="post-title">{{ post.title }}</h1>
        </div>

        <div class="post-info">
            <div class="info-item">
                <BIconGeoAlt class="info-icon" />
                <span>{{ post.location }}</span>
            </div>
            <div class="info-item">
                <BIconCalendar class="info-icon" />
                <span>{{ post.created_at }}</span>
            </div>
        </div>

        <div class="post-map">
            <GoogleMapComponent
                :disableDefaultUi="true"
                gestureHandling="none"
                :marker="{ lat: post.lat, lng: post.lng }"
                :zoom="15"
                class="map"
            />
        </div>

        <div class="post-content">
            <p v-if="showFullContent">{{ post.content }}</p>
            <p v-else>{{ contentPreview }}</p>

            <button
                v-if="post.content && post.content.length > 200"
                @click="toggleContent"
                class="show-more-btn"
            >
                {{ showFullContent ? 'Show Less' : 'Show More' }}
            </button>
        </div>
    </div>


</template>

<style scoped>
.post-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid #dee2e6;
}

.post-user {
    display: flex;
    align-items: center;
    gap: 12px;
    grid-column: 1;
}

.post-user :deep(.avatar) {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f8f9fa;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.post-author {
    font-size: 1.1rem;
    font-weight: 500;
    color: #495057;
    margin: 0;
}

.post-title {
    grid-column: 2;
    font-size: 1.75rem;
    color: #333;
    margin: 0;
    text-align: center;
}

.post-info {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    gap: 16px;
}

.info-item {
    display: flex;
    align-items: center;
    color: #666;
    font-size: 0.9rem;
}

.info-icon {
    margin-right: 6px;
}

.post-preview {
    height: 200px;
    background-color: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    cursor: pointer;
}

.post-map {
    margin: 20px 0 28px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.map {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    height: 50vh !important;
    min-height: 300px;
    width: 100%;
    transition: box-shadow 0.3s ease;
}

@media (max-width: 768px) {
    .map {
        height: 40vh !important;
        min-height: 250px;
    }
}

.post-content {
    line-height: 1.6;
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
    margin: 15px 0;
    text-align: center;
}

.show-more-btn:hover {
    background-color: #e9ecef;
    border-color: #ced4da;
}

.show-more-btn:active {
    background-color: #dde2e6;
    transform: translateY(1px);
}

.show-more-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.map-container {
    height: 300px;
    background-color: #f5f5f5;
    border-radius: 8px;
    margin-bottom: 10px;
}

.map-details {
    padding: 10px;
}
</style>
