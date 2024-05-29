<template>
    <div class="flex items-center justify-center w-10 h-10 shrink-0">
        <svg
            width="40"
            height="40"
            viewBox="0 0 40 40"
        >
            <!-- Adjusted circle radius to 18 to make diameter 36 pixels -->
            <circle
                cx="20"
                cy="20"
                r="18"
                fill="none"
                stroke="#d9d9d9"
                stroke-width="3"
            />

            <circle
                cx="20"
                cy="20"
                r="18"
                fill="none"
                stroke="#FE9A24"
                stroke-width="3"
                stroke-linecap="round"
                :style="circleStyle"
            />

            <!-- Image positioned to be centered within the larger circle -->
            <image
                href="https://picsum.photos/900"
                x="11.5"
                y="9.5"
                height="20"
                width="17"
                preserveAspectRatio="xMidYMid meet"
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

const radius = 18 // Updated radius for the new circle size
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
