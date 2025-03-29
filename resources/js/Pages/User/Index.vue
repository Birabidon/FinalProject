<script setup>
import { Link, router } from '@inertiajs/vue3'
import { throttle, debounce } from 'lodash'; // throttle - invokes this function only once in 1 second, so it doesn't make a request on every key stroke
import { ref, watch } from 'vue'
import PaginationLink from "@/Components/PaginationLink.vue";

const props = defineProps({
    users: Object,
    searchTerm: String,
    can: Object
})

const search = ref(props.searchTerm);

watch(search, debounce( // debounce - waits for 0.5 second after the last key stroke
    (value) => router.get('/users', { search: value }, { preserveState: true }),
    500
));

// (value) => router.get('/users', { search: value }, { preserveState: true })); // preserveState: true - to keep the search value in the input field)
// // when search changes makes a get request to the same page with the search query (http://127.0.0.1:8000/users?search=aleks)

// https://www.youtube.com/watch?v=tZKYvtFGPDM&list=PL38wFHH4qYZXCW2rlBLNdHi5cv-v_qlXO&index=15&ab_channel=LearnwithJon
const getDate = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: "numeric",
        month: "long",
        day: "numeric",
    }
);

const handleDelete = (id) => {
    router.delete(`/users/${id}`, {
        preserveState: true,
    });
}
</script>

<template>
    <Head title="UserList"/>
    <div>
        <div>
            <div class="flex justify-end mb-4">
                <div class="w-1/4">
                    <input type="search" placeholder="Search" v-model="search"/>
                </div>

            </div>
        </div>
        <table>

            <thead>
            <tr class="bg-slate-300">
                <th>Avatar</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registration Date</th>
                <th v-if="can.delete_user">Delete</th>
            </tr>
            </thead>

            <tbody>
            <Link
                as="tr"
                v-for="user in users.data"
                :key="user.id"
                :href="`/users/${user.id}`">
                <td>
                    <img :src="user.avatar ? ('storage/' + user.avatar) : 'storage/avatars/default.jpg'" class="avatar">
                </td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ getDate(user.created_at) }}</td>
                <td v-if="can.delete_user">
                    <button class="bg-red-500 w-6 h-6 rounded-full" @click.stop="handleDelete(user.id)"></button> <!-- @click.stop - to stop the event from bubbling up the dom tree. (without it Link would redirect to another page) -->
                </td>
            </Link>
            </tbody>
        </table>
    </div>

    <!--    Pagination links    -->
    <div>
        <PaginationLink :paginator="users"/>


<!--        Its the same as PaginationLink but before making it a component
    <Link v-for="link in users.links" -->
<!--              :key="link.label"-->
<!--              v-html="link.label"-->
<!--              :href="String(link.url)"-->
<!--              class="p-1 mx-1"-->
<!--              :class="{'text-slate-300': !link.url, 'text-blue-500 font-medium':link.active }">-->

<!--        </Link>-->
        <!-- v-html - to render arrow symbol, bc it's coded like &laquo;
            users.links - is created auto by pagination method in the controller
         -->

<!--        <p class="text-skate-600 text-sm">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results</p>-->
    </div>
</template>

<style scoped>

</style>
