<script setup>
import {ref, watch} from "vue";
import {debounce} from "lodash";

const props = defineProps({
    searchTerm: {
        type: String,
        default: ''
    },
})

const search = ref(props.searchTerm);
const emit = defineEmits(['search']);

watch(search, debounce( // debounce - waits for 0.5 second after the last key stroke
    () => emit('search', search.value),
    500
));


</script>

<template>
    <div class="flex justify-end mb-4">
        <div class="w-1/4">
            <input type="search" placeholder="Search" v-model="search"/>
        </div>

    </div>
</template>

<style scoped>

</style>
