<script setup>
import {defineProps, watch, ref} from 'vue';
import Avatar from '@/Components/Avatar.vue';
import {router, Link} from "@inertiajs/vue3";
import ShowUserPosts from "@/Pages/User/Profile/ShowUserPosts.vue";
import PostCard from "@/Components/Posts/PostCard.vue";
import SearchBar from "@/Components/SearchBar.vue";


// Rewrite everything using this: https://inertiajs.com/partial-reloads
// Implement what is written in copilot!!!!!!!!!!!!!!!!!!!!
const props = defineProps(
    {
        user: Object,
        can: Object,
        posts: Object,
        info: Object,
        currentTab: String,
        searchTerm: String
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

const handleSearch = (term) => {
    router.get(`/users/${props.user.id}`, {
            tab: props.currentTab,
            search: term,
        },
        {
            preserveState: true,
            only: [props.currentTab, 'searchTerm', 'currentTab'],
            preserveScroll: true
        });
}

const handleRedirect = (tab) => {
    router.get(`/users/${props.user.id}`, {
            tab: tab,
        },
        {
            preserveState: true,
            only: [tab, 'searchTerm', 'currentTab'],
            preserveScroll: true
        });
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
                    <Avatar :path="user.avatar" :alt="user.name"></Avatar>
                </div>
                <h1>{{ user.name }}</h1>
            </div>
            <div class="buttons-container" v-if="can.edit">
                <Link class="create-post" href="/posts/create">Create Post</Link>
                <Link class="edit-profile" :href="`/users/${user.id}/edit`">Edit Profile</Link>
            </div>
        </div>

        <div class="nav-bar">
            <button @click.prevent="handleRedirect('posts')" :class="['nav-link', currentTab === 'posts' ? 'active' : '']">Posts</button>
            <button @click.prevent="handleRedirect('info')" :class="['nav-link', currentTab === 'info' ? 'active' : '']">Info</button>
            <button @click.prevent="handleRedirect('rates')" :class="['nav-link', currentTab === 'rates' ? 'active' : '']">Rates</button>
            <Link href="/comments" class="nav-link">Comments</Link>
            <Link href="/friends" class="nav-link">Friends</Link>
            <Link href="/photos" class="nav-link">Photos</Link>
        </div>

        <div class="tab-content">
            <div
                v-if="currentTab === 'posts'"
                class="posts-container"
            >

                <div>
                    <div class="search-container">
                        <SearchBar
                            :searchTerm="searchTerm"
                            @search="handleSearch"
                        />
                    </div>

                    <div v-if="posts.length === 0" class="empty-state">
                        {{ searchTerm ? 'No posts matching your search.' : 'No posts yet.' }}
                    </div>

                    <PostCard
                        v-if="posts"
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                    />
                </div>


            </div>
            <div
                v-if="currentTab === 'info'"
                class="info-container"
            >
                <div class="info-card">
                    <h2 class="info-title">User Information</h2>

                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ user.email }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Posts</div>
                        <div class="info-value">{{ info.posts_count }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Account Created</div>
                        <div class="info-value">{{ info.account_created }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Account Age</div>
                        <div class="info-value">{{ info.account_age }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ info.last_updated }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Unique Locations</div>
                        <div class="info-value">{{ info.unique_locations }}</div>
                    </div>
                </div>
            </div>

            <div
                v-if="currentTab === 'rates'"
                class="posts-container"
            >

                <div>
                    <div class="search-container">
                        <SearchBar
                            :searchTerm="searchTerm"
                            @search="handleSearch"
                        />
                    </div>

                    <div v-if="posts.length === 0" class="empty-state">
                        {{ searchTerm ? 'No posts matching your search.' : 'No posts yet.' }}
                    </div>

                    <PostCard
                        v-if="posts"
                        v-for="post in posts"
                        :key="post.id"
                        :post="post"
                    />
                </div>
            </div>
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
    font-weight: bold;
    margin: 0 10px;
    padding: 10px 20px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
    transition: background-color 0.3s;
}

.nav-link.active {
    color: #1877f2;
    background-color: #e7f0ff;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    position: relative;
    top: 1px; /* Subtle downward shift */
}

.nav-link:hover {
    background-color: #f0f2f5;
    border-radius: 5px;
}

.tab-content {
    background-color: #fff; /* Set background color to white */
    padding: 20px;
    margin-top: 10px; /* Reduce margin-top */
    border-radius: 0; /* Remove border radius */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Take full width */
    flex-grow: 1; /* Take remaining space */
}

/* Info section */
.info-container {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.info-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    padding: 30px;
}

.info-title {
    font-size: 24px;
    color: #1877f2;
    margin-bottom: 25px;
    text-align: center;
    border-bottom: 2px solid #f0f2f5;
    padding-bottom: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding: 10px 0;
    border-bottom: 1px solid #f0f2f5;
}

.info-label {
    font-weight: bold;
    color: #555;
    flex: 1;
}

.info-value {
    color: #333;
    flex: 2;
}
</style>
