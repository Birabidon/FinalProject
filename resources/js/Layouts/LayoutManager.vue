<template>
    <component :is="$page.props.auth.user ? 'AppLayout' : 'GuestLayout'">
        <slot />
    </component>
</template>

<script>
import AppLayout from '@/Layouts/App.vue'
import GuestLayout from '@/Layouts/Guest.vue'

export default {
    components: {
        AppLayout,
        GuestLayout
    },
}
</script>

<style scoped>

</style>
