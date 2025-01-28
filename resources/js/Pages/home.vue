<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref } from 'vue'
import { GoogleMap, Marker, InfoWindow } from 'vue3-google-map'
import { usePage } from '@inertiajs/vue3'
import LayoutManager from "@/Layouts/LayoutManager.vue";

const { props } = usePage()
</script>

<template>
    <Head title="Home"/>
    <p>{{$page.props.auth.user?.name || 'No user'}}</p>
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
            selectedMarker: null
        }
    },
    methods: {
        handleMarkerClick(marker) {
            this.selectedMarker = marker
        }
    }
}
</script>
<style scoped>

</style>
