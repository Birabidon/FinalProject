<script setup>
import {ref} from "vue";

const props = defineProps({
    averageRating: {
        type: Number,
        default: 0
    },
    votesCount: {
        type: Number,
        default: 0
    },
    userRating: {
        type: Number,
        default: 0
    },
    votable: {
        type: Boolean,
        default: true
    }
})

const hoverRating = ref(0);

const emit = defineEmits(['updateRating']);

</script>

<template>
    <div class="info-item rating">
        <div class="star-container-wrapper">
            <div class="star-container">
                <!-- Star code remains the same -->
                <i v-for="star in 5" :key="star" class="star-wrapper" :class="{'non-votable': !votable}">
                    <i class="pi pi-star-fill star-fill"
                       :class="{'yellow': star <= (Math.round(averageRating) || 0)}"></i>
                    <i class="pi pi-star star-border"
                       :class="{
                          'grey': votesCount === 0 || (votesCount > 0 && star > (Math.max(Math.round(averageRating), hoverRating) || 0)),
                          'black': star <= (hoverRating || userRating || 0)
                       }"
                       v-on="votable ? {
                          click: () => emit('updateRating', star),
                          mouseenter: () => hoverRating = star,
                          mouseleave: () => hoverRating = 0
                       } : {}"
                    ></i>
                </i>
            </div>
            <span class="rating-text">({{ averageRating ? averageRating.toFixed(2) : '0.00' }})</span>
        </div>
        <span class="votes-count">votes: {{ votesCount }}</span>
    </div>
</template>

<style scoped>

.star-container {
    display: flex;
}

.pi-star,
.pi-star-fill {
    color: #ddd;
    font-size: 1.2rem;
    margin-right: 2px;
    transition: color 0.2s;
}

.star-wrapper {
    position: relative;
    display: inline-block;
    margin-right: 2px;
    cursor: pointer;
}

.non-votable {
    cursor: default;
}

.non-votable .star-border {
    cursor: default;
}

.star-fill {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    color: transparent; /* Default */
}

.yellow {
    color: #ffd800; /* Fill color */
}

.star-border {
    position: relative;
    z-index: 2; /* This ensures border appears on top */
    color: transparent; /* Default border color */
}
.grey {
    color: lightgrey;
}

.black {
    color: black; /* Highlighted border color */
}


.star-container-wrapper {
    display: flex;
    align-items: center;
}

.votes-count {
    font-size: 0.8rem;
    color: #666;
    margin-top: -5px;
    margin-left: 5px;
    display: block;
}

.info-item.rating {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
</style>
