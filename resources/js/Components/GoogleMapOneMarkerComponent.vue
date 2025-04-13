<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { defineProps } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'

const props = defineProps({
    marker: {
        type: Object,
        required: true
    },
    zoom: {
        type: Number,
        default: 10
    },
    disableDefaultUi: {
        type: Boolean,
        default: false
    },
    gestureHandling: {
        type: String,
        default: "auto"
    },
    centerLat: {
        type: Number,
        default: null
    },
    centerLng: {
        type: Number,
        default: null
    }
})


// const marker = props.marker

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY

// restriction to the map -- in future

/*
:draggable="false"
:scrollwheel="false"
:zoomControl="false"
:keyboardShortcuts="false"
clickableIcons="false"
controlSize="0"
:disableDoubleClickZoom="true"
:fullscreenControl="false"
:mapTypeControl="false"
:streetViewControl="false"


*/
</script>

<template>
    <!-- mapTypeId="satellite" -->
    <GoogleMap
        :api-key="apiKey"
        class="map"
        :center="{ lat: centerLat ?? marker.lat, lng: centerLng ?? marker.lng }"
        :zoom="zoom"
        mapId="7d2f8294b343021c"
        :disableDefaultUi="disableDefaultUi"
        :gestureHandling="gestureHandling"
        :zoomControl="true"
        mapTypeId="satellite"
    >
        <AdvancedMarker
            :options="{ position: { lat: marker.lat, lng: marker.lng } }"
        />
    </GoogleMap>


</template>

<style scoped>
.map{
    width: 100%;
    height: 60vh
}
</style>
