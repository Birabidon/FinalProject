<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref, onMounted, watch, defineProps } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({
    markers: Array,
    handleAnyMapClick: Function,
    handleMapDBClick: Function,
    style: String,
})

const markers = props.markers

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY
</script>

<template>
    <!-- mapTypeId="satellite" -->
    <div @click="handleAnyMapClick">
        <GoogleMap
            ref="mapRef"
            :api-key="apiKey"
            :style="style"
            :center="{ lat: 56.9496, lng: 24.1052 }"
            :zoom="10"
            mapId="7d2f8294b343021c"
            :disableDoubleClickZoom="true"
            @dblclick="handleMapDBClick"
        >
            <AdvancedMarker
                v-for="(marker, index) in markers"
                :key="index"
                :options="{ position: { lat: marker.lat, lng: marker.lng } }"
            >
                <InfoWindow>
                    <div>
                        {{ marker.LocationName }}}
                    </div>

                    <div>{{ marker.title }}</div>
                </InfoWindow>
            </AdvancedMarker>

            <slot>

            </slot>
        </GoogleMap>
    </div>


</template>

<style scoped>

</style>
