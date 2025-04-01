<script setup>
import { defineProps } from 'vue';
import Avatar from '@/Components/Avatar.vue';
import {router} from "@inertiajs/vue3";
// import { Inertia } from '@inertiajs/inertia';

const props = defineProps(
    {
        user: Object,
        can: Object
    }
);

const accountAge = (createdAt) => {
    const createdDate = new Date(createdAt);
    const currentDate = new Date();
    const ageInMilliseconds = currentDate - createdDate;
    return Math.floor(ageInMilliseconds / (1000 * 60 * 60 * 24)); // age in days
}

const handleDelete = (id) => {
    router.delete(`/users/${id}`);
}
</script>

<template>
    <div class="user-profile">
        <div class="avatar-container">
            <Avatar :avatar="user.avatar" :alt="user.name"></Avatar>
        </div>
        <h1>{{ user.name }}'s Profile</h1>
        <div class="user-details">
            <p><strong>Email:</strong> {{ user.email }}</p>
            <p><strong>Account Created:</strong> {{ new Date(user.created_at).toLocaleDateString() }}</p>
            <p><strong>Account Age:</strong> {{ accountAge(user.created_at) }} days</p>
        </div>
        <button v-if="can.delete_user" @click="handleDelete(user.id)" class="delete-button">Delete User</button>
    </div>
</template>

<style scoped>
.user-profile {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    background-color: #ffffff;
    font-family: 'Arial', sans-serif;
}

.avatar-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
}

h1 {
    font-size: 28px;
    color: #333333;
    margin-bottom: 20px;
}

.user-details {
    text-align: left;
    margin-bottom: 20px;
}

.user-details p {
    font-size: 18px;
    color: #555555;
    margin: 10px 0;
}

.delete-button {
    background-color: #e3342f;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #cc1f1a;
}
</style>
