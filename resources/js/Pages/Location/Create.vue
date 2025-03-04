<script setup>
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import {defineProps} from "vue";

const props = defineProps({
    lat: {
        type: Number,
        required: true
    },
    lng: {
        type: Number,
        required: true
    }
})

const form = useForm({
    name: null,
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
            <TextInput name="Location name" v-model="form.name" :message="form.errors.name"/>
            <TextInput name="Latitude" v-model="form.lat" :message="form.errors.lat"/>
            <TextInput name="Longitude" v-model="form.lng" :message="form.errors.lng"/>
            <button type="submit" :disabled="form.processing">Create</button>
        </form>
    </div>
</template>

<style scoped>

</style>
