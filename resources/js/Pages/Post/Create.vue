<script setup>
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import GoogleMapComponent from "@/Components/GoogleMapOneMarkerComponent.vue";
import {defineProps, onBeforeUnmount} from "vue";
import TiptapEditor from "@/Components/TiptapEditor.vue";
const props = defineProps({
    location: {
        type: String,
        required: true
    },
    lat: {
        type: Number,
        required: true
    },
    lng: {
        type: Number,
        required: true
    }
})

const form = useForm({
    title: "",
    content: "",
    lat: props.lat,
    lng: props.lng,
    location: props.location,
    images: [],
    imageTempUrls: [],
})

const submit = () => {
    form.post("/posts")
}

const handleImageUpload = ({file, tempUrl}) => {
    form.images.push(file);

    form.imageTempUrls[tempUrl] = file;
}
</script>

<template>
    <div>
        <h2>Create a new post</h2>
        <form @submit.prevent="submit">
            <TextInput name="Post title" v-model="form.title" :message="form.errors.title"/>
            <TiptapEditor
                v-model="form.content"
                @imageUpload="handleImageUpload"
            />
            <div v-if="form.errors.content" class="error-message">{{ form.errors.content }}</div>
<!--            <textarea-->
<!--                name="content"-->
<!--                v-model="form.content">-->

<!--            </textarea>-->
            <button type="submit" :disabled="form.processing">Create</button>
        </form>


        <GoogleMapComponent
            :disableDefaultUi="true"
            gestureHandling="none"
            :marker="{ lat: props.lat, lng: props.lng }"
            :zoom="15"
        />
    </div>
</template>

<style scoped>
div {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #4f46e5;
    padding-bottom: 10px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

/* Style specifically for the TiptapEditor container */
:deep(.editor-wrapper) {
    margin-bottom: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 6px;
}

button[type="submit"] {
    background-color: #4f46e5;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    align-self: flex-start;
    transition: background-color 0.2s;
}

button[type="submit"]:hover {
    background-color: #4338ca;
}

button[type="submit"]:disabled {
    background-color: #a5a5a5;
    cursor: not-allowed;
}

.error-message {
    color: #dc2626;
    font-size: 14px;
    margin-top: -15px;
}

/* Make the map container have a defined height */
:deep(.google-map) {
    height: 300px;
    width: 100%;
    margin-top: 20px;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #ddd;
}
</style>

<!--
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
