<template>
    <div class="flex items-center justify-center w-10 h-10 shrink-0">
        <svg
            width="40"
            height="40"
            viewBox="0 0 40 40"
        >
            <circle
                cx="20"
                cy="20"
                r="14"
                fill="none"
                stroke="#EAEAEA"
                stroke-width="4"
            />
            <circle
                cx="20"
                cy="20"
                r="14"
                fill="none"
                stroke="#FE9A24"
                stroke-width="4"
                stroke-linecap="round"
                :style="circleStyle"
            />
        </svg>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
    percent: {
        type: Number,
        required: true,
    },
})

const radius = 14
const circumference = 2 * Math.PI * radius

const circleStyle = computed(() => {
    const offset = circumference - (props.percent / 100) * circumference
    return {
        strokeDasharray: `${circumference} ${circumference}`,
        strokeDashoffset: offset.toString(),
        transition: 'stroke-dashoffset 0.5s ease 0s, stroke 0.5s ease',
    }
})
</script>

<style scoped></style>
