<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref, onMounted } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'
import { usePage, Link } from '@inertiajs/vue3'

const { props } = usePage()
const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY


</script>

<template>
    <p class="p-4 bg-green-200" v-if="$page.props.flash.message">{{ $page.props.flash.message }}</p>
    <Head title="Home"/>
    <div id="map"></div>

    <!-- mapTypeId="satellite" -->
    <div @click="handleMapClick">
        <GoogleMap
            :api-key="apiKey"
            style="width: 100%; height: 90vh"
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
            >
                <InfoWindow v-model="alwaysOpen">
                    <div v-if="$page.props.auth.user">
                        To create new Marker/Place click <Link href="/locations/create" :data="{ lat: temporaryMarker.lat, lng: temporaryMarker.lng }" style="color: blue">here</Link>
                    </div>
                    <div v-else>
                        To create new Marker, you should <Link href="/login" style="color: blue">login</Link>
                    </div>
                </InfoWindow>
            </AdvancedMarker>
        </GoogleMap>
    </div>


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
        markers: Array
    },
    data() {
        return {
            temporaryMarker: null,
            alwaysOpen: true,
        }
    },
    methods: {
        handleMapClick(e) {
            this.temporaryMarker = null
        },
        handleMapDBClick(e) {
            const lat = e.latLng.lat()
            const lng = e.latLng.lng()
            this.temporaryMarker = { lat, lng, title: `Marker at (${lat}, ${lng})` }
            this.alwaysOpen = true
            console.log('Temp mark create' + this.temporaryMarker)
        }
    }
}
</script>
<style scoped>

</style>
