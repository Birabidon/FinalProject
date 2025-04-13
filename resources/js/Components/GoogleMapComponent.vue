<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref, onMounted, watch, defineProps, defineEmits } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({
    markers: Array,
    center: {
        type: Object,
        required: true
    },
    zoom: {
        type: Number,
        default: 10
    },
    mapId: {
        type: String,
        default: "7d2f8294b343021c"
    },
    disableDoubleClickZoom: {
        type: Boolean,
        default: false
    },
    disableDefaultUi: {
        type: Boolean,
        default: false
    },
    gestureHandling: {
        type: String,
        default: "auto"
    },
    disableInfoWindow: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['ready', 'mapDBClick', 'markerClick:marker']);

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
const mapRef = ref(null);
const googleApi = ref(null);

watch(() => mapRef.value?.ready, (ready) => {
    if (ready) {
        googleApi.value = mapRef.value.api;
        emit('ready', googleApi.value);
    }
});

const truncateContent = (content, length=100) => {
    return content.length > length ? content.substring(0, length) + '...' : content;
}
</script>

<template>
    <GoogleMap
        ref="mapRef"
        :api-key="apiKey"
        :center="props.center"
        :zoom="props.zoom"
        :mapId="props.mapId"
        :disableDoubleClickZoom="props.disableDoubleClickZoom"
        :disableDefaultUi="props.disableDefaultUi"
        :gestureHandling="props.gestureHandling"

        @dblclick="$emit('mapDBClick', $event)"
    >
        <AdvancedMarker
            v-for="(marker, index) in props.markers"
            :key="index"
            :options="{ position: { lat: marker.lat, lng: marker.lng } }"
            @click="() => emit('markerClick', marker)"
        >
            <InfoWindow v-if="!disableInfoWindow">
                <div>Location name: {{ marker.location }}</div>
                <div>Post title: {{ marker.title }}</div>
                <div>{{ truncateContent(marker.content) }}</div>
            </InfoWindow>
        </AdvancedMarker>

        <slot></slot>
    </GoogleMap>
</template>

<style scoped>

</style>
