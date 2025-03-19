<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <div>
        <h1>Users</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in props.users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
