<template>
    <div
        class="flex w-full items-start gap-4 rounded-lg p-4"
        :style="`
            background: linear-gradient(
                    0deg,
                    ${hexToRgba(card.color)} 0%,
                    ${hexToRgba(card.color)} 100%
                ),
                #fff;`"
    >
        <achievement-icon
            :percent="card.percent"
            :color="card.color"
            :date="card?.date"
            :icon="card?.icon?.path"
        />
        <div class="flex w-full flex-col items-start gap-1">
            <h5 class="text-base font-semibold text-gray-800">
                {{ card.title }}
            </h5>
            <p
                v-if="card?.subtitle || card?.story"
                class="flex items-center gap-1 text-xs font-semibold"
                :style="`color: ${card?.color};`"
            >
                {{ card?.subtitle }}

                <point-icon v-if="card?.subtitle && card?.story" />
                {{ card?.story }}
            </p>
            <p class="mt-1 text-xs font-normal text-black">
                {{ card.description }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementIcon from '@/components/AchievementIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    showStoryName: { type: Boolean, default: true },
})

function hexToRgba(hex, alpha = 10) {
    // Remove the leading '#' if it is present
    hex = hex.replace(/^#/, '')

    // Convert 3-digit hex to 6-digit hex
    if (hex.length === 3) {
        hex = hex
            .split('')
            .map((char) => char + char)
            .join('')
    }

    // Parse the r, g, b values
    const intVal = parseInt(hex, 16)
    const r = (intVal >> 16) & 255
    const g = (intVal >> 8) & 255
    const b = intVal & 255

    // Convert alpha to the range [0, 1]
    alpha = alpha / 100

    return `rgba(${r}, ${g}, ${b}, ${alpha})`
}
</script>

<style scoped></style>
