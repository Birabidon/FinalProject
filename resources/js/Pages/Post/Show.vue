<script setup>
import {ref, onMounted, computed} from 'vue'
import { BIconGeoAlt, BIconCalendar, BIconPerson } from 'bootstrap-icons-vue'
import Avatar from "@/Components/Avatar.vue";
import GoogleMapComponent from "@/Components/GoogleMapOneMarkerComponent.vue";
import Menu from "primevue/menu";
import {Link, router} from "@inertiajs/vue3";
import {toNumber} from "@vue/shared";
import StarsScale from "@/Components/StarsScale.vue";
import TiptapEditor from "@/Components/TiptapEditor.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    can: {
        type: Object,
        required: true
    },
});

// const showFullContent = ref(false);
// const contentPreview = ref('');

// onMounted(() => {
//     if (props.post.content && props.post.content.length > 200) {
//         contentPreview.value = props.post.content.substring(0, 200) + '...';
//     } else {
//         contentPreview.value = props.post.content;
//         showFullContent.value = true;
//     }
// });

// const toggleContent = () => {
//     showFullContent.value = !showFullContent.value;
// };

const menu = ref(null);
const menuItems = computed(() => {
    const items = [];

    // Only add Edit if user has permission
    if (props.can.edit) {
        items.push({
            label: 'Edit',
            icon: 'pi pi-pencil',
            command: () => editPost(props.post.id)
        });
    }

    // Only add Delete if user has permission
    if (props.can.delete) {
        items.push({
            label: 'Delete',
            icon: 'pi pi-trash',
            command: () => deletePost(props.post.id)
        });
    }

    // Always add separator if we have at least one item above
    if (items.length > 0) {
        items.push({
            separator: true
        });
    }

    // Always add Share option
    items.push({
        label: 'Share',
        icon: 'pi pi-share-alt',
        command: () => sharePost(props.post.id)
    });

    return items;
});

const toggleMenu = (event) => {
    menu.value.toggle(event);
};

const editPost = (id) => {
    router.get(`/posts/${id}/edit`);
};

const deletePost = () => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(`/posts/${props.post.id}`);
    }
};

const sharePost = (id) => {
    // Share logic
    console.log('Share post', id);
};


const ratePost = (rating) => {
    console.log('Rating:', rating);
    // Send rating to backend and update post average rating
    router.post(`/posts/${props.post.id}/rate`, {
        rating: rating
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// const ratePost = (rating) => {
//     userRating.value = rating;
//     isSubmitting.value = true;
//
//     router.post(`/posts/${props.post.id}/rate`,
//         { rating },
//         {
//             preserveState: true,
//             onSuccess: (page) => {
//                 if (page.props.flash?.success) {
//                     props.post.average_rating = page.props.flash.average_rating;
//                     ratingSuccess.value = true;
//                     setTimeout(() => ratingSuccess.value = false, 2000);
//                 }
//             },
//             onFinish: () => isSubmitting.value = false
//         }
//     );
// };

</script>

<template>
    <div class="page-container">
        <div class="post-container">
            <div class="post-header">
                <div class="post-user">
                    <Avatar :avatar="post.user.avatar" :alt="post.user.name" />
                    <h2 class="post-author">{{ post.user.name }}</h2>
                </div>
                <h1 class="post-title">{{ post.title }}</h1>

                <div class="menu-container">
                    <button class="menu-trigger" @click="toggleMenu">
                        <i class="pi pi-ellipsis-v"></i>
                    </button>
                    <Menu ref="menu" :model="menuItems" :popup="true">
                        <template #item="{ item, props }">
                            <!-- Handle separator -->
                            <div v-if="item.separator" class="custom-separator"></div>

                            <!-- Handle regular menu items - icons only -->
                            <a
                                v-else
                                v-bind="props.action"
                                class="custom-menu-item"
                            >
                                <i :class="[item.icon, 'custom-icon']"></i>
                            </a>
                        </template>
                    </Menu>
                </div>
            </div>

            <div class="post-info">
                <div class="info-row">
                    <div class="info-item">
                        <BIconGeoAlt class="info-icon" />
                        <span>{{ post.location }}</span>
                    </div>
                    <div class="info-item">
                        <BIconCalendar class="info-icon" />
                        <span>{{ post.created_at }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <StarsScale
                        :averageRating="post.average_rating"
                        :votesCount="post.votes_count"
                        :userRating="post.user_rating"
                        @updateRating="ratePost"
                        :votable="!!$page.props.auth.user"
                    />
                </div>
            </div>

            <div class="post-map">
                <GoogleMapComponent
                    :disableDefaultUi="true"
                    gestureHandling="none"
                    :marker="{ lat: post.lat, lng: post.lng }"
                    :zoom="15"
                    class="map"
                />
            </div>

            <div class="post-content">
                <TiptapEditor
                    v-model="post.content"
                    :editable="false"
                    :key="post.id" /> <!-- insures that editor is updated after changing post -->
                />
            </div>
        </div>
    </div>


</template>

<style scoped>

/* Three dots styles */
/* Menu container positioning */
.menu-container {
    position: absolute;
    right: 0;
    top: 0;
    margin: 2rem;
}

/* Trigger button styling */
.menu-trigger {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.menu-trigger:hover {
    background-color: #f0f2f5;
}

/* Menu items styling - icons only */
.custom-menu-item {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    color: #495057;
    cursor: pointer;
    transition: background-color 0.2s;
    width: 44px;
}

.custom-menu-item:hover {
    background-color: #f5f7f9;
}

/* Icon styling */
.custom-icon {
    font-size: 1.2rem;
    color: #6c757d;
}

/* Menu separator */
.custom-separator {
    height: 1px;
    background-color: #dee2e6;
    margin: 0.5rem 0;
}

/* Page styles */

.page-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    min-height: calc(100vh - 72px);
    padding: 20px;
    background: lightblue;
}

.post-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 60%;
    min-width: 850px;
    padding: 20px;
    background: white;
    position: relative;
}

.post-header {
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid #dee2e6;
    width: 100%;
}

.post-user {
    display: flex;
    align-items: center;
    gap: 12px;
    grid-column: 1;
}

.post-user :deep(.avatar) {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f8f9fa;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.post-author {
    font-size: 1.1rem;
    font-weight: 500;
    color: #495057;
    margin: 0;
}

.post-title {
    font-size: 1.75rem;
    color: #333;
    margin: 0;
    text-align: center;
}

.post-info {
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    width: 100%;
    margin-bottom: 20px;
    gap: 16px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.info-item {
    display: flex;
    flex-direction: row;
    align-items: center;
    color: #666;
    font-size: 0.9rem;
}

.info-icon {
    margin-right: 6px;
}

.post-preview {
    height: 200px;
    background-color: #f5f5f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    cursor: pointer;
}

.post-map {
    margin: 20px 0 28px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.map {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    height: 50vh !important;
    min-height: 300px;
    min-width: 500px;
    transition: box-shadow 0.3s ease;
}

@media (max-width: 768px) {
    .map {
        height: 40vh !important;
        min-height: 250px;
    }
}

.post-content {
    line-height: 1.6;
    width: 100%;
}



</style>
