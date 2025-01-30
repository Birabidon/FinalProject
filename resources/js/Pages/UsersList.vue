<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    users: Object,
})

const getDate = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: "numeric",
        month: "long",
        day: "numeric",
})
</script>

<template>
    <div>
        <table>

            <thead>
                <tr class="bg-slate-300">
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td>
                        <img :src="user.avatar ? ('storage/' + user.avatar) : 'storage/avatars/default.jpg'" class="avatar">
                    </td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ getDate(user.created_at) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

<!--    Pagination links    -->
    <div>
        <Link v-for="link in users.links"
              :key="link.label"
              v-html="link.label"
              :href="link.url"
              class="p-1 mx-1"
              :class="{'text-slate-300': !link.url, 'text-blue-500 font-medium':link.active }">

        </Link>

        <p class="text-skate-600 text-sm">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results</p>
    </div>
</template>

<style scoped>

</style>
