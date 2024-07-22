<template>
    <div class="flex flex-col items-center gap-2">
        <div
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white"
        >
            <svg
                height="40"
                viewBox="0 0 40 40"
                width="40"
            >
                <!-- Adjusted circle radius to 18 to make diameter 36 pixels -->
                <circle
                    v-if="percent > 0"
                    cx="20"
                    cy="20"
                    fill="none"
                    r="18"
                    stroke="#d9d9d9"
                    stroke-width="4"
                />

                <circle
                    v-if="percent > 0"
                    :stroke="color"
                    :style="circleStyle"
                    cx="20"
                    cy="20"
                    fill="none"
                    r="18"
                    stroke-linecap="round"
                    stroke-width="4"
                />

                <!-- Image positioned to be centered within the larger circle -->
                <image
                    v-if="icon"
                    :href="icon"
                    :width="percent > 0 ? 17 : 30"
                    :x="percent > 0 ? 11.5 : 5"
                    :y="percent > 0 ? 9.5 : 3.5"
                    preserveAspectRatio="xMidYMid meet"
                />
            </svg>
        </div>

        <span
            v-if="time"
            :style="`color: ${color}`"
            class="whitespace-nowrap rounded-full bg-white px-1.5 py-0.5 text-[10px] font-semibold"
        >
            {{ time }}
        </span>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

const props = defineProps({
    icon: {
        type: String,
        default: null,
    },
    percent: {
        type: Number,
        required: true,
    },
    color: {
        type: String,
        required: true,
    },
    time: {
        type: String,
        default: null,
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
