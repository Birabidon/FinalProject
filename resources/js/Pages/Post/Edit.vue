<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import GoogleMapComponent from '@/Components/GoogleMapComponent.vue';
import { AdvancedMarker } from 'vue3-google-map';
import TiptapEditor from "@/Components/TiptapEditor.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
    }
});

const form = useForm({
    title: props.post.title,
    content: props.post.content,
    location: props.post.location,
    lat: props.post.lat,
    lng: props.post.lng,
    images: [],
    imageTempUrls: [],
});

const markerPosition = ref({
    lat: props.post.lat,
    lng: props.post.lng
});

// Update form coordinates when marker is moved
watch(markerPosition, (newPosition) => {
    form.lat = newPosition.lat;
    form.lng = newPosition.lng;

    // Get location name using geocoder
    if (geocoder.value) {
        changeLocationName(markerPosition.value);
    }
},
    { deep: true }
);

// Handle map click to reposition marker
const handleMapClick = (e) => {
    markerPosition.value = {
        lat: e.latLng.lat(),
        lng: e.latLng.lng()
    };

};

const geocoder = ref(null);
const initGeocoder = (Api) => {
    geocoder.value = new Api.Geocoder();
};

const changeLocationName = (latlng) => {
    geocoder.value.geocode(
        {
            location: latlng,
            language: 'en'  // Force English language results
        },
        (results, status) => {
            if (status === 'OK' && results[0]) {
                form.location = results[0].formatted_address;
            }
        }
    );
};

const submit = () => {
    console.log(form)
    form.post(`/posts/${props.post.id}`);
};

const handleImageUpload = ({file, tempUrl}) => {
    form.images.push(file);

    form.imageTempUrls[tempUrl] = file;
    console.log(form);
}
</script>

<template>
    <div class="container">
        <div class="header-section">
            <h1 class="page-title">Edit Post</h1>
        </div>

        <form @submit.prevent="submit" class="edit-form">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="form-input"
                    required
                >
                <div v-if="form.errors.title" class="error-message">{{ form.errors.title }}</div>
            </div>

            <div class="form-group">
                <label class="form-label">Content</label>
                <TiptapEditor
                    v-model="form.content"
                    @imageUpload="handleImageUpload"
                />
                <div v-if="form.errors.content" class="error-message">{{ form.errors.content }}</div>
            </div>

            <div class="form-group">
                <label for="location" class="form-label">
                    Location <span class="location-hint">(Location name is automatically determined based on map coordinates)</span>
                </label>
                <div class="location-display">
                    <span class="location-text">{{ form.location }}</span>
                    <i class="pi pi-map-marker location-icon"></i>
                </div>
                <div v-if="form.errors.location" class="error-message">{{ form.errors.location }}</div>
                <input type="hidden" v-model="form.location" id="location">
            </div>

            <div class="coordinates-container">
                <div class="coordinate-group">
                    <label for="lat" class="form-label">Latitude</label>
                    <input
                        id="lat"
                        :value="markerPosition.lat"
                        @input="markerPosition.lat = parseFloat($event.target.value)"
                        type="number"
                        step="0.0001"
                        class="form-input"
                        required
                    >
                    <div v-if="form.errors.lat" class="error-message">{{ form.errors.lat }}</div>
                </div>
                <div class="coordinate-group">
                    <label for="lng" class="form-label">Longitude</label>
                    <input
                        id="lng"
                        :value="markerPosition.lng"
                        @input="markerPosition.lng = parseFloat($event.target.value)"
                        type="number"
                        step="0.0001"
                        class="form-input"
                        required
                    >
                    <div v-if="form.errors.lng" class="error-message">{{ form.errors.lng }}</div>
                </div>
            </div>

            <button
                type="submit"
                class="submit-button"
                :disabled="form.processing"
            >
                Update Post
            </button>
        </form>

        <div class="map-container">
            <p class="map-info">Click on the map to update the location</p>
            <GoogleMapComponent
                :center="{ lat: markerPosition.lat, lng: markerPosition.lng }"
                :zoom="14"
                :disableInfoWindow="true"
                @ready="initGeocoder"
                @click="handleMapClick"
                style="height: 400px; width: 100%;"
            >
                <AdvancedMarker
                    :options="{
                        position: { lat: markerPosition.lat, lng: markerPosition.lng },
                        title: form.location
                    }"
                />
            </GoogleMapComponent>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.header-section {
    margin-bottom: 2rem;
    border-bottom: 2px solid #4f46e5;
    padding-bottom: 10px;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.edit-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.location-hint {
    font-size: 0.875rem;
    font-weight: normal;
    color: #6b7280;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    box-sizing: border-box;
}

.form-textarea {
    resize: vertical;
}

.location-display {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
}

.location-text {
    flex-grow: 1;
    color: #374151;
}

.location-icon {
    color: #6b7280;
    margin-left: 0.5rem;
}

.coordinates-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.coordinate-group {
    width: 50%;
}

.error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.submit-button {
    background-color: #2563eb;
    color: white;
    border: none;
    border-radius: 0.25rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #1d4ed8;
}

.submit-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.map-container {
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
}

.map-info {
    padding: 0.5rem;
    margin: 0;
    background-color: #f3f4f6;
    font-size: 0.875rem;
}
</style>
