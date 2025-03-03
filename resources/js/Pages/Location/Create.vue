<script>
import TextInput from "@/Components/TextInput.vue";

export default {
    name: "CreateLocation",
    components: {TextInput}
}
</script>

<template>
    <div>
        <h2>Create a new location</h2>
        <form action="/locations/">
            <TextInput name="name" v-model="form.name" :message="form.errors.name"/>
        </form>
    </div>
</template>

<style scoped>

</style>
