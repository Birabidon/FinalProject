<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    paginator: {
        type: Object,
        required: true
    }
});

const makeLabel = (label) => {
    if (label.includes('Previous')) {
        return '<<';
    } else if (label.includes('Next')) {
        return '>>';
    } else {
        return label;
    }
};
</script>

<template>
    <div class="flex justify-between items-start">
        <div class="flex items-center rounded-mb overflow-hidden shadow-lg">
            <div v-for="(link, index) in paginator.links" :key="index">
                <!--        component render Link if url not null, otherwise span-->
                <Link
                    v-if="link.url"
                    :href="String(link.url)"
                    v-html="makeLabel(link.label)"
                    class="border-x border-slate-50 w-12 h-12 grid place-items-center bg-white"
                    :class="{
                        'hover:bg-slate-50': link.url,
                        'text-zinc-400': !link.url,
                        'font-bold text-blue-500': link.active,
                    }"
                />
                <span
                    v-else
                    v-html="makeLabel(link.label)"
                    class="border-x border-slate-50 w-12 h-12 grid place-items-center bg-white text-zinc-400"
                />
            </div>
        </div>
    </div>


    <p class="text-skate-600 text-sm">Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} results</p>

</template>

<style scoped>

</style>
