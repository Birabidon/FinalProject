<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref } from 'vue'
import { GoogleMap, Marker, InfoWindow } from 'vue3-google-map'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const markers = ref(props.markers)
const selectedMarker = ref(null)

const handleMarkerClick = (marker) => {
    selectedMarker.value = marker
}
</script>

<template>
    <head>
        <title>Home</title>
    </head>

    <AppLayout>
        <div id="map"></div>
<!--            <GoogleMap-->
<!--                api-key="AIzaSyAnlSRXNY5cHoUQ1jCzU526gaRjrDgNgj0"-->
<!--                style="width: 100%; height: 500px"-->
<!--                :center="{ lat: 56.9496, lng: 24.1052 }"-->
<!--                :zoom="10"-->
<!--            >-->
<!--                <Marker-->
<!--                    v-for="(marker, index) in markers"-->
<!--                    :key="index"-->
<!--                    :options="{ position: { lat: marker.lat, lng: marker.lng }, title: marker.title }"-->
<!--                    @click="handleMarkerClick(marker)"-->
<!--                >-->
<!--                    <InfoWindow v-if="selectedMarker === marker">-->
<!--                        <div>{{ marker.title }}</div>-->
<!--                    </InfoWindow>-->
<!--                </Marker>-->
<!--            </GoogleMap>-->
    </AppLayout>

</template>

<script>
import AppLayout from '@/Layouts/App.vue'
export default {
    components: {
        AppLayout
    },
    props: {
        markers: Object
    }
}
</script>
<style scoped>

</style>
