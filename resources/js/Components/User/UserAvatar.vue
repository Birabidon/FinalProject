

<script setup>
import { defineProps, computed } from 'vue';
import { Link } from "@inertiajs/vue3";
import Avatar from "@/Components/Avatar.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    size: {
        type: String,
        default: '48px' // Accepts any CSS size value: '1rem', '50px', etc.
    }
});

const userNameStyle = computed(() => {
    // Extract numeric part and unit from size
    const sizeMatch = props.size.match(/^([0-9]*\.?[0-9]+)(\D+)$/);
    if (!sizeMatch) return {};

    const [, sizeValue, unit] = sizeMatch;
    // Calculate font size as a proportion of avatar size
    const fontSizeValue = parseInt(sizeValue) * 0.5; // Minimum of 12 units
    if (unit==='px' && fontSizeValue < 12) {
        return {
            fontSize: '12px'
        };
    }

    return {
        fontSize: `${fontSizeValue}${unit}`
    };
});
</script>

<template>
    <Link class="user-avatar-container" :href="`/users/${user.id}`">
        <div class="avatar-wrapper" :style="{ width: size, height: size }">
            <Avatar
                :avatar="user.avatar"
                :link="`/users/${user.id}`"
                :alt="user.name"
            />
        </div>
        <p class="user-name" :style="userNameStyle">{{ user.name }}</p>
    </Link>
</template>

<style scoped>
.user-avatar-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}

.avatar-wrapper {
    position: relative;
}

.avatar-wrapper :deep(.avatar) {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-weight: 500;
    margin: 0;
    color: #343a40;
}
</style>
