<script setup lang="ts">
import { ref, defineProps } from 'vue';

const props = defineProps({
    width: {
        type: String,
        default: '24px',
    },
    height: {
        type: String,
        default: '24px',
    },
    fill: {
        type: String,
        default: 'none',
    },
    stroke: {
        type: String,
        default: '#000000',
    },

    loading: {
        type: Boolean,
        default: false,
    },
});

</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :width="width"
        :height="height"
        viewBox="0 0 24 24"
        :class="{ 'rotate-loading': loading }"
    >
        <path
            :fill="fill"
            :stroke="stroke"
            stroke-width="2"
            d="M1.7507,16.0022 C3.3517,20.0982 7.3367,23.0002 11.9997,23.0002 C18.0747,23.0002 22.9997,18.0752 22.9997,12.0002 M22.2497,7.9982 C20.6487,3.9012 16.6627,1.0002 11.9997,1.0002 C5.9247,1.0002 0.9997,5.9252 0.9997,12.0002 M8.9997,16.0002 L0.9997,16.0002 L0.9997,24.0002 M22.9997,0.0002 L22.9997,8.0002 L14.9997,8.0002"
        />
    </svg>
</template>

<style scoped>
@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.rotate-loading {
    animation: rotate 1s linear infinite;
}
</style>
