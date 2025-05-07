<script setup>
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import GoogleMapComponent from "@/Components/GoogleMapComponent.vue";
import {defineProps, onBeforeUnmount, ref, watch} from "vue";
import TiptapEditor from "@/Components/TiptapEditor.vue";
import {AdvancedMarker} from "vue3-google-map";

const props = defineProps({
    location: {
        type: String,
        default: ''
    },
    lat: {
        type: Number,
        default: 56.9496
    },
    lng: {
        type: Number,
        default: 24.1052
    }
});

const form = useForm({
    title: "",
    content: "",
    lat: props.lat,
    lng: props.lng,
    location: props.location,
    images: [],
    imageTempUrls: [],
});

const markerPosition = ref({
    lat: props.lat,
    lng: props.lng
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

const geocoder = ref(null);
const initGeocoder = (Api) => {
    geocoder.value = new Api.Geocoder();

    if (!form.location) {
        changeLocationName({ lat: form.lat, lng: form.lng })
    }
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

const handleMapClick = (e) => {
    markerPosition.value = {
        lat: e.latLng.lat(),
        lng: e.latLng.lng()
    };
};

const submit = () => {
    changeLocationName(markerPosition.value); // Just in case so we always had the latest location name

    form.post("/posts")
}

const handleImageUpload = ({file, tempUrl}) => {
    form.images.push(file);

    form.imageTempUrls[tempUrl] = file;
}
</script>

<template>
    <div class="container">
        <div class="header-section">
            <h1 class="page-title">Create a new Post</h1>
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
                Create Post
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
    color: #333;
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


<!--<style scoped>-->
<!--div {-->
<!--    max-width: 900px;-->
<!--    margin: 0 auto;-->
<!--    padding: 20px;-->
<!--    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;-->
<!--}-->

<!--h2 {-->
<!--    font-size: 28px;-->
<!--    margin-bottom: 20px;-->
<!--    color: #333;-->
<!--    border-bottom: 2px solid #4f46e5;-->
<!--    padding-bottom: 10px;-->
<!--}-->

<!--form {-->
<!--    display: flex;-->
<!--    flex-direction: column;-->
<!--    gap: 20px;-->
<!--    margin-bottom: 30px;-->
<!--}-->

<!--/* Style specifically for the TiptapEditor container */-->
<!--:deep(.editor-wrapper) {-->
<!--    margin-bottom: 10px;-->
<!--    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);-->
<!--    border-radius: 6px;-->
<!--}-->

<!--button[type="submit"] {-->
<!--    background-color: #4f46e5;-->
<!--    color: white;-->
<!--    border: none;-->
<!--    border-radius: 6px;-->
<!--    padding: 12px 20px;-->
<!--    font-size: 16px;-->
<!--    font-weight: 600;-->
<!--    cursor: pointer;-->
<!--    align-self: flex-start;-->
<!--    transition: background-color 0.2s;-->
<!--}-->

<!--button[type="submit"]:hover {-->
<!--    background-color: #4338ca;-->
<!--}-->

<!--button[type="submit"]:disabled {-->
<!--    background-color: #a5a5a5;-->
<!--    cursor: not-allowed;-->
<!--}-->

<!--.error-message {-->
<!--    color: #dc2626;-->
<!--    font-size: 14px;-->
<!--    margin-top: -15px;-->
<!--}-->

<!--/* Make the map container have a defined height */-->
<!--:deep(.google-map) {-->
<!--    height: 300px;-->
<!--    width: 100%;-->
<!--    margin-top: 20px;-->
<!--    border-radius: 6px;-->
<!--    overflow: hidden;-->
<!--    border: 1px solid #ddd;-->
<!--}-->
<!--</style>-->

<!-- MB I WILL NEED THIS IN THE FUTURE, WHO KNOWS.
 To efficiently load and display posts on a map without fetching all of them, you can implement a server-side solution that only retrieves posts within the current map bounds. This approach ensures that you only load the posts that are visible to the user.

Here is a step-by-step approach to achieve this:

1. **Update the backend to handle requests for posts within specific bounds.**
2. **Update the frontend to send the current map bounds to the backend and fetch the relevant posts.**

### Backend (Laravel)

1. **Add a new route and controller method to handle fetching posts within bounds.**

In `routes/web.php`:
```php
use App\Http\Controllers\PostController;

Route::get('/posts-within-bounds', [PostController::class, 'getPostsWithinBounds']);
```

In `app/Http/Controllers/PostController.php`:
```php
public function getPostsWithinBounds(Request $request)
{
    $validated = $request->validate([
        'northEastLat' => 'required|numeric',
        'northEastLng' => 'required|numeric',
        'southWestLat' => 'required|numeric',
        'southWestLng' => 'required|numeric',
    ]);

    $posts = Post::whereBetween('lat', [$validated['southWestLat'], $validated['northEastLat']])
                 ->whereBetween('lng', [$validated['southWestLng'], $validated['northEastLng']])
                 ->get();

    return response()->json($posts);
}
```

### Frontend (Vue)

2. **Update the Vue component to fetch posts within the current map bounds.**

In `resources/js/Pages/Post/Create.vue`:
```vue
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { GoogleMap } from 'vue3-google-map';

const posts = ref([]);
const mapRef = ref(null);

const fetchPostsWithinBounds = async () => {
    const bounds = mapRef.value.getBounds();
    const northEast = bounds.getNorthEast();
    const southWest = bounds.getSouthWest();

    const response = await axios.get('/posts-within-bounds', {
        params: {
            northEastLat: northEast.lat(),
            northEastLng: northEast.lng(),
            southWestLat: southWest.lat(),
            southWestLng: southWest.lng(),
        }
    });

    posts.value = response.data;
};

onMounted(() => {
    fetchPostsWithinBounds();
});

const handleMapIdle = () => {
    fetchPostsWithinBounds();
};
</script>

<template>
    <div>
        <GoogleMap
            ref="mapRef"
            :api-key="import.meta.env.VITE_GOOGLE_MAPS_API_KEY"
            @idle="handleMapIdle"
            style="width: 100%; height: 90vh"
        >
            <AdvancedMarker
                v-for="post in posts"
                :key="post.id"
                :options="{ position: { lat: post.lat, lng: post.lng }, title: post.title }"
            >
                <InfoWindow>
                    <div>{{ post.title }}</div>
                </InfoWindow>
            </AdvancedMarker>
        </GoogleMap>
    </div>
</template>
```

This approach ensures that only the posts within the current map bounds are fetched and displayed, improving performance and user experience.



If the user wants to view the entire map and see every post, you can implement a mechanism to load posts in chunks or use clustering to manage the display of a large number of posts efficiently. Here are two approaches:

### 1. Load Posts in Chunks
You can load posts in chunks as the user pans or zooms the map. This ensures that the map remains responsive and doesn't get overwhelmed by too many markers.

### 2. Use Clustering
Clustering groups nearby markers into a single cluster marker, which expands to individual markers as the user zooms in.

Here is an example using the clustering approach with the `vue3-google-map` library:

```vue
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { GoogleMap, MarkerClusterer } from 'vue3-google-map';

const posts = ref([]);
const mapRef = ref(null);

const fetchAllPosts = async () => {
    const response = await axios.get('/posts');
    posts.value = response.data;
};

onMounted(() => {
    fetchAllPosts();
});
</script>

<template>
    <div>
        <GoogleMap
            ref="mapRef"
            :api-key="import.meta.env.VITE_GOOGLE_MAPS_API_KEY"
            style="width: 100%; height: 90vh"
        >
            <MarkerClusterer>
                <AdvancedMarker
                    v-for="post in posts"
                    :key="post.id"
                    :options="{ position: { lat: post.lat, lng: post.lng }, title: post.title }"
                >
                    <InfoWindow>
                        <div>{{ post.title }}</div>
                    </InfoWindow>
                </AdvancedMarker>
            </MarkerClusterer>
        </GoogleMap>
    </div>
</template>
```

This approach ensures that the map remains performant even when displaying a large number of posts.
-->
