<script setup>
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import {defineProps} from "vue";

const props = defineProps({
    title: String
    lat: {
        type: Number,
        required: true
    },
    lng: {
        type: Number,
        required: true
    }
})

console.log(props.title) // Error This is undefined!!! FIX IT FUTURE NIKITA

const form = useForm({
    title: props.title,
    lat: props.lat,
    lng: props.lng,
})

const submit = () => {
    form.post("/locations")
}
</script>

<template>
    <div>
        <h2>Create a new location</h2>
        <form @submit.prevent="submit">
            <TextInput name="Location title" v-model="form.title" :message="form.errors.title"/>
            <TextInput name="Latitude" v-model="form.lat" :message="form.errors.lat"/>
            <TextInput name="Longitude" v-model="form.lng" :message="form.errors.lng"/>
            <button type="submit" :disabled="form.processing">Create</button>
        </form>
    </div>
</template>

<style scoped>

</style>
