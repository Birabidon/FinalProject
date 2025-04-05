<script setup>
import { defineProps } from 'vue';
import Avatar from '@/Components/Avatar.vue';
import {router, Link} from "@inertiajs/vue3";

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
    <div class="profile-page">
        <div class="cover">
            <img :src="user.cover ? ('/storage/' + user.cover) : ('/storage/avatars/default.jpg')" alt="cover" class="cover-image">
        </div>

        <div class="profile-header">
            <div class="left-section">
                <div class="avatar-container">
                    <Avatar :avatar="user.avatar" :alt="user.name"></Avatar>
                </div>
                <h1>{{ user.name }}</h1>
            </div>
            <div class="buttons-container">
                <Link class="create-post">Create Post</Link>
                <Link class="edit-profile" v-if="can.edit">Edit Profile</Link>
            </div>
        </div>

        <div class="nav-bar">
            <Link href="/posts" class="nav-link">Posts</Link>
            <Link href="/info" class="nav-link">Info</Link>
            <Link href="/comments" class="nav-link">Comments</Link>
            <Link href="/likes" class="nav-link">Likes</Link>
            <Link href="/friends" class="nav-link">Friends</Link>
            <Link href="/photos" class="nav-link">Photos</Link>
        </div>

        <div class="information">
            <!-- Content that changes based on nav-bar selection -->
        </div>
    </div>
</template>
<!--            <button v-if="can.delete_user" @click="handleDelete(user.id)" class="delete-button">Delete User</button>-->

<style scoped>
.profile-page {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.cover {
    width: 100%;
    height: 300px; /* Reduced height */
    overflow: hidden;
    position: relative;
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 20px;
    margin-top: -50px;
    border-radius: 0; /* Remove border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    width: 100%; /* Take full width */
}

.left-section {
    margin-left: 5rem;
    display: flex;
    align-items: center;
    gap: 5rem;
}

.avatar-container {
    width: 200px; /* Increased size */
    height: 200px; /* Increased size */
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #fff;
    margin-right: 20px; /* Increased margin */
    position: relative;
    top: -60px; /* Adjust this value to control the overlap */
    box-shadow: 0 0 0 4px #fff; /* Outline */
}

.avatar-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

h1 {
    font-size: 4rem; /* Increased font size */
    color: #333;
}

.buttons-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-right: 5rem;
}

.create-post, .edit-profile {
    background-color: #1877f2;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s;
}

.create-post:hover, .edit-profile:hover {
    background-color: #165dbb;
}

.nav-bar {
    display: flex;
    justify-content: center;
    background-color: #fff; /* Set background color to white */
    padding: 10px;
    margin-top: 10px; /* Reduce margin-top */
    border-radius: 0; /* Remove border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Take full width */
}

.nav-link {
    margin: 0 10px;
    padding: 10px 20px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
    transition: background-color 0.3s;
}

.nav-link:hover {
    background-color: #f0f2f5;
    border-radius: 5px;
}

.information {
    background-color: #fff; /* Set background color to white */
    padding: 20px;
    margin-top: 10px; /* Reduce margin-top */
    border-radius: 0; /* Remove border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Take full width */
    flex-grow: 1; /* Take remaining space */
}
</style>
