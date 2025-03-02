<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY
</script>

<template>
    <p class="p-4 bg-green-200" v-if="$page.props.flash.message">{{ $page.props.flash.message }}</p>
    <Head title="Home"/>
    <div id="map"></div>

    <!-- mapTypeId="satellite" -->
    <GoogleMap
        :api-key="apiKey"
        style="width: 100%; height: 100vh"
        :center="{ lat: 56.9496, lng: 24.1052 }"
        :zoom="10"
        mapId="7d2f8294b343021c"
        :disableDoubleClickZoom="true"
        @dblclick="handleMapDBClick"
    >
        <AdvancedMarker
            v-for="(marker, index) in markers"
            :key="index"
            :options="{ position: { lat: marker.lat, lng: marker.lng }, title: marker.title }"
        >
            <InfoWindow>
                <div>{{ marker.title }}</div>
            </InfoWindow>
        </AdvancedMarker>
        <AdvancedMarker
            v-if="temporaryMarker"
            :options="{ position: { lat: temporaryMarker.lat, lng: temporaryMarker.lng }, title: temporaryMarker.title }"
        />
    </GoogleMap>

</template>

<script>
import AppLayout from '@/Layouts/App.vue'
import GuestLayout from '@/Layouts/Guest.vue'

export default {
    components: {
        AppLayout,
        GuestLayout
    },
    props: {
        markers: Object
    },
    data() {
        return {
            selectedMarker: null,
            temporaryMarker: null
        }
    },
    methods: {
        handleMarkerClick(marker) {
            this.selectedMarker = marker
        },
        handleMapDBClick(e) {
            const lat = e.latLng.lat()
            const lng = e.latLng.lng()
            console.log('Map clicked', lat, lng)
            this.temporaryMarker = { lat, lng, title: `Marker at (${lat}, ${lng})` }
            this.selectedMarker = null
        }
    }
}
</script>
<style scoped>

</style>
