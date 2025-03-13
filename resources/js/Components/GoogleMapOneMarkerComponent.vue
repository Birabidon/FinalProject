<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { defineProps } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'

const props = defineProps({
    marker: Object
})

const marker = props.marker

console.log(props.marker)

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
    <div>
        <GoogleMap
            :api-key="apiKey"
            class="map"
            :center="{ lat: marker.lat, lng: marker.lng }"
            :zoom="10"
            mapId="7d2f8294b343021c"
            :disableDefaultUi="true"
            gestureHandling="none"
        >
            <AdvancedMarker
                :options="{ position: { lat: marker.lat, lng: marker.lng } }"
            />
        </GoogleMap>
    </div>


</template>

<style scoped>
.map{
    width: 60%;
    height: 60vh
}
</style>
