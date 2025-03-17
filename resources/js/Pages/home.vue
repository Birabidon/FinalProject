<script setup>
// https://www.npmjs.com/package/vue3-google-map#installation google maps vue 3
import { ref, onMounted, watch, defineProps } from 'vue'
import { GoogleMap, AdvancedMarker, InfoWindow } from 'vue3-google-map'
import { Link } from '@inertiajs/vue3'
import GoogleMapComponent from "@/Components/GoogleMapComponent.vue"

const props = defineProps({
    postsMarkers: {
        type: Array,
        required: true
    }
})

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY

const googleApi = ref(null)

let getPlaceName = null

const initGeocoder = (Api) => {
    // getting googleApi from GoogleMapComponent, when map is ready, GoogleMapComponent emits 'ready' event
    googleApi.value = Api
    const geocoder = new googleApi.value.Geocoder()

    getPlaceName = (latlng, marker) => {
        geocoder.geocode({ location: latlng }, (results, status) => {
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
}

const temporaryMarker = ref(null)
const alwaysOpen = ref(true)

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

</script>

<template>
    <Head title="Home"/>

    <p class="p-4 bg-green-200" v-if="$page.props.flash.message">{{ $page.props.flash.message }}</p>

    <div id="map"></div>

    <!-- mapTypeId="satellite" -->
    <div @click="handleMapClick">
        <GoogleMapComponent
            ref="mapRef"
            :markers="props.postsMarkers"
            :center="{ lat: 56.9496, lng: 24.1052 }"
            :zoom="10"
            :disableDoubleClickZoom="true"
            @mapDBClick="handleMapDBClick"
            @ready="initGeocoder"
            style="height: 90vh; width: 100%;"
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
    </div>


</template>

<style scoped>

</style>


<!--<AdvancedMarker-->
<!--    v-if="temporaryMarker"-->
<!--    :options="{ position: { lat: temporaryMarker.lat, lng: temporaryMarker.lng }, title: temporaryMarker.title }"-->
<!--&gt;-->
<!--<InfoWindow v-model="alwaysOpen">-->
<!--    <div v-if="temporaryMarker.LocationName">-->
<!--        <div>-->
<!--            {{ temporaryMarker.LocationName }}-->
<!--        </div>-->
<!--        <div v-if="$page.props.auth.user">-->
<!--            To create new Marker/Place click <Link href="/posts/create" :data="{ location: temporaryMarker.LocationName,lat: temporaryMarker.lat, lng: temporaryMarker.lng }" style="color: blue">here</Link>-->
<!--        </div>-->
<!--        <div v-else>-->
<!--            To create new Marker, you should <Link href="/login" style="color: blue">login</Link>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div v-else>-->
<!--        Loading...-->
<!--    </div>-->
<!--</InfoWindow>-->
<!--</AdvancedMarker>-->
