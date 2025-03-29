<script setup>
import { ref, computed, onMounted, onUnmounted, } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    flash: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const visible = ref(true);

const typeClass = computed(() => {
    return props.flash.type === 'success' ? 'success' : 'error';
});

const close = () => {
    // visible.value = false;
    console.log('Close');
    emit('close');
};

onMounted(() => {
    setTimeout(close, 5000); // Auto close after 5 seconds
});
</script>

<template>
    <div :class="['flash-message', typeClass, { 'flash-message--visible': visible }]">
        <span>{{ flash.message }}</span>
        <button @click="close">X</button>
    </div>
</template>

<!--<script>-->
<!--export default {-->
<!--    props: {-->
<!--        flash: {-->
<!--            type: Object,-->
<!--            required: true,-->
<!--        },-->
<!--    },-->
<!--    data() {-->
<!--        return {-->
<!--            visible: true,-->
<!--        };-->
<!--    },-->
<!--    computed: {-->
<!--        typeClass() {-->
<!--            return this.flash.type === 'success' ? 'success' : 'error';-->
<!--        },-->
<!--    },-->
<!--    mounted() {-->
<!--        // console.log('message', this.message);-->
<!--        setTimeout(this.close, 5000); // Auto close after 5 seconds-->
<!--    },-->
<!--    methods: {-->
<!--        close() {-->
<!--            this.visible = false;-->
<!--        },-->
<!--    }-->
<!--};-->
<!--</script>-->

<style scoped>
.flash-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

.flash-message--visible {
    opacity: 1;
}

.flash-message--success {
    background-color: #d4edda;
    color: #155724;
}

.flash-message--error {
    background-color: #f8d7da;
    color: #721c24;
}

.flash-message button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}
</style>
