<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import {ref, onMounted, defineProps, onBeforeUnmount} from 'vue';
import { AdvancedMarker, InfoWindow } from 'vue3-google-map';
import {Link, router} from '@inertiajs/vue3';
import GoogleMapComponent from "@/Components/GoogleMapComponent.vue";
import MarkerDetailsModal from "@/Components/MarkerDetailsModal.vue";
import ShowPost from "@/Pages/Post/Show.vue";

// Dropdown menu:  https://www.youtube.com/watch?v=mQJTGDI6noE&list=PLLQuc_7jk__Wa8IoZ2s0J-ql_MIisndtZ&index=10&ab_channel=TheCodeholic (0:00 - 10:00)


// TODO: Make that postMarkers would be only posts location and to get details must be done request to the server

const props = defineProps({
    postsMarkers: {
        type: Array,
        required: true
    },
    can: {
        type: Object
    },
    post: {
        type: Object
    }
})

const googleApi = ref(null)
const geocoder = ref(null);

const temporaryMarker = ref(null)
const alwaysOpen = ref(true)


// -- MODAL ---
const isModalOpen = ref(false);
const postPreviewRef = ref(null);


// Add these refs to your existing refs
const scrollTimeout = ref(null);
const scrollObserver = ref(null);

// Detects if user scrolls near the postPreview and aligns it to the top of the screen
const setupScrollSnapping = () => {
    if (!postPreviewRef.value) return;

    // Track when user stops scrolling
    let scrollTimer = null;

    const handleScroll = () => {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(() => {
            // User has stopped scrolling
            const rect = postPreviewRef.value.getBoundingClientRect();

            // If post is partially visible (between -100px and 100px from top of viewport)
            // but not perfectly aligned
            if (rect.top > -100 && rect.top < 200 && rect.top !== 0) {
                postPreviewRef.value.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 300);
    };

    window.addEventListener('scroll', handleScroll);

    // Store for cleanup
    scrollObserver.value = {
        cleanup: () => window.removeEventListener('scroll', handleScroll)
    };
};

// Add these lifecycle hooks
onMounted(() => {
    // Your existing onMounted code...
    setupScrollSnapping();
});

onBeforeUnmount(() => {
    if (scrollObserver.value && postPreviewRef.value) {
        scrollObserver.value.cleanup();
    }
});

const initGeocoder = (Api) => {
    // getting googleApi from GoogleMapComponent, when map is ready, GoogleMapComponent emits 'ready' event
    googleApi.value = Api
    geocoder.value = new Api.Geocoder()
}

const getPlaceName = (latlng, marker) => {
    geocoder.value.geocode({
            location: latlng,
            language: 'en'  // Force English language results
        },
        (results, status) => {
        if (status === 'OK') {
            if (results[0]) {
                marker.LocationName = results[0].formatted_address
            } else {
                console.log('No results found')
            }
        } else {
            console.log('Geocoder failed due to:', status)
        }
    })
}

const handleMapClick = (e) => {
    temporaryMarker.value = null
}

const handleMapDBClick = (e) => {
    const lat = e.latLng.lat()
    const lng = e.latLng.lng()
    temporaryMarker.value = { lat, lng, title: `Marker at (${lat}, ${lng})` }

    if (getPlaceName){
        getPlaceName({ lat, lng }, temporaryMarker.value)
    }

    alwaysOpen.value = true
    console.log('Temp mark create' + temporaryMarker.value)
}

const handleMarkerClick = (post) => { // Write request for post and comments in future
    router.get(`/`,
        {
            post: post.id
        },
        {
            preserveState: true,
            only: ['post', 'can'],
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = true;
            }
        });
    // selectedPost.value = post;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleShowMore = () => {
    postPreviewRef.value.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}
</script>

<template>
    <Head title="Home"/>
    <!-- mapTypeId="satellite" -->
    <div @click="handleMapClick" class="map-container">
        <GoogleMapComponent
            ref="mapRef"
            :markers="props.postsMarkers"
            :center="{ lat: 56.9496, lng: 24.1052 }"
            :zoom="10"
            :disableDoubleClickZoom="true"
            :disableInfoWindow="true"
            @mapDBClick="handleMapDBClick"
            @ready="initGeocoder"
            @markerClick="handleMarkerClick"
            style="height: calc(100vh - 64px); width: 100%;"
        >
            <AdvancedMarker
                v-if="temporaryMarker"
                :options="{ position: { lat: temporaryMarker.lat, lng: temporaryMarker.lng }, title: temporaryMarker.title }"
            >
                <InfoWindow v-model="alwaysOpen">
                    <div v-if="temporaryMarker.LocationName">
                        <div>
                            {{ temporaryMarker.LocationName }}
                        </div>
                        <div v-if="$page.props.auth.user">
                            To create new Marker/Place click <Link href="/posts/create" :data="{ location: temporaryMarker.LocationName,lat: temporaryMarker.lat, lng: temporaryMarker.lng }" style="color: blue">here</Link>
                        </div>
                        <div v-else>
                            To create new Marker, you should <Link href="/login" style="color: blue">login</Link>
                        </div>
                    </div>
                    <div v-else>
                        Loading...
                    </div>
                </InfoWindow>
            </AdvancedMarker>
        </GoogleMapComponent>

        <MarkerDetailsModal
            v-if="post"
            :is-open="isModalOpen"
            :marker="post"
            @close="closeModal"
            @showMore="handleShowMore"
        />
    </div>

    <div class="post-preview" ref="postPreviewRef">
        <ShowPost
            v-if="post"
            :post="post"
            :can="can"
        />
    </div>
</template>

<style scoped>
.map-container {
    position: relative;
    overflow: hidden;
}
</style>


<!-- FOR FUTURE, for markers grouping-->
<!--const initGeocoder = (Api) => {-->
<!--googleApi.value = Api;-->
<!--const geocoder = new googleApi.value.Geocoder();-->

<!--getPlaceName = (latlng, marker) => {-->
<!--geocoder.geocode({ location: latlng }, (results, status) => {-->
<!--if (status === 'OK') {-->
<!--if (results[0]) {-->
<!--marker.LocationName = results[0].formatted_address;-->
<!--marker.AddressComponents = results[0].address_components;-->
<!--} else {-->
<!--console.log('No results found');-->
<!--}-->
<!--} else {-->
<!--console.log('Geocoder failed due to:', status);-->
<!--}-->
<!--});-->
<!--};-->
<!--};-->

<!--const temporaryMarker = ref(null);-->
<!--const alwaysOpen = ref(true);-->

<!--const handleMapClick = (e) => {-->
<!--temporaryMarker.value = null;-->
<!--};-->

<!--const handleMapDBClick = (e) => {-->
<!--const lat = e.latLng.lat();-->
<!--const lng = e.latLng.lng();-->
<!--temporaryMarker.value = { lat, lng, title: `Marker at (${lat}, ${lng})` };-->

<!--if (getPlaceName) {-->
<!--getPlaceName({ lat, lng }, temporaryMarker.value);-->
<!--}-->

<!--alwaysOpen.value = true;-->
<!--console.log('Temp mark create' + temporaryMarker.value);-->
<!--};-->

<!--// Function to group markers by city-->
<!--const groupMarkersByCity = (markers) => {-->
<!--const groupedMarkers = {};-->
<!--markers.forEach(marker => {-->
<!--const cityComponent = marker.AddressComponents.find(component => component.types.includes('locality')); // or "neighborhood" or postal_code or ...-->
<!--const city = cityComponent ? cityComponent.long_name : 'Unknown';-->
<!--if (!groupedMarkers[city]) {-->
<!--groupedMarkers[city] = [];-->
<!--}-->
<!--groupedMarkers[city].push(marker);-->
<!--});-->
<!--return groupedMarkers;-->
<!--};-->

<!--const groupedMarkers = ref(groupMarkersByCity(props.postsMarkers));-->
