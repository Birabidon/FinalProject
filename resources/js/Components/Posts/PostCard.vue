<script setup>
import {Link} from "@inertiajs/vue3";
import Avatar from "@/Components/Avatar.vue";
import GoogleMapComponent from "@/Components/GoogleMapOneMarkerComponent.vue";
import { BIconGeoAlt, BIconCalendar, BIconPerson } from 'bootstrap-icons-vue'


const props = defineProps({
    post: {
        type: Object,
        required: true
    },
});

</script>

<template>
    <div class="post-card">
        <Link :href="`posts/${post.id}`" class="post-link">
            <div class="card-left-side">
                <div class="card-header" v-if="post.user">
                    <Avatar />
                    <h2 class="post-author">{{ post.user.name }}</h2>
                </div>
                <div class="card-info">
                    <div class="info-item">
                        <h3>{{ post.title }}</h3>
                    </div>

                    <div class="info-item">
                        <BIconGeoAlt class="info-icon" />
                        <span>{{ post.location }}</span>
                    </div>

                    <div class="info-item">
                        <BIconGeoAlt class="info-icon" />
                        <span>{{ post.lat + ' ' + post.lng }}</span>
                    </div>

                    <div class="info-item">
                        <BIconCalendar class="info-icon" />
                        <span>{{ post.created_at }}</span>
                    </div>
                </div>
            </div>

            <div class="card-right-side">
                <GoogleMapComponent
                    :disableDefaultUi="true"
                    gestureHandling="none"
                    :marker="{ lat: post.lat, lng: post.lng }"
                    :zoom="15"
                    :zoomControl="false"
                    class="map"
                />
            </div>

        </Link>
    </div>
</template>

<style scoped>
.post-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 1.5rem;
    border: 1px solid #eaecef;
}

.post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}

.post-link {
    display: flex;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.card-left-side {
    flex: 1.25;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
}

.card-right-side {
    flex: 1;
    width: 180px;
    min-width: 180px;
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.card-header :deep(.avatar) {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    margin-right: 12px;
    border: 2px solid #f8f9fa;
}

.post-author {
    font-size: 0.95rem;
    font-weight: 500;
    color: #4b5563;
    margin: 0;
}

.card-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #6b7280;
    line-height: 1.4;
}

.info-item:first-child {
    margin-bottom: 8px;
}

.info-item h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    line-height: 1.3;
}

.info-icon {
    margin-right: 8px;
    flex-shrink: 0;
    font-size: 0.95rem;
    color: #4b5563;
}

.map {
    height: 100%;
    width: 100%;
    min-height: 180px;
}

@media (max-width: 640px) {
    .post-link {
        flex-direction: column;
    }

    .card-right-side {
        width: 100%;
        height: 160px;
        order: -1;
    }

    .card-left-side {
        padding: 1rem;
    }
}
</style>
