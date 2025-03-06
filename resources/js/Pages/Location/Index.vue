<script>
export default {
    name: "index",
    props: {
        markers: Array
    }
}
</script>

<template>
    <div v-for="location in markers">
        <h2>{{ location.name }}</h2>
        <p>{{ location.lat }}</p>
        <p>{{ location.lng }}</p>
    </div>
</template>

<style scoped>

</style>
