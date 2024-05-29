<template>
    <div class="flex gap-4 items-start w-full rounded-lg p-3 bg-slate-200">
        <div class="flex flex-col w-full items-start">
            <p
                class="text-xs text-slate-400 font-medium flex items-center gap-1"
            >
                {{ card.name }}
                <span
                    v-if="showStoryName"
                    class="flex items-center gap-1"
                >
                    <point-icon />
                    {{ card.story }}
                </span>
            </p>
            <h5 class="text-base text-gray-800 font-semibold my-1пц">
                Character creation
            </h5>
            <p class="text-xs text-gray-600 font-normal">
                Define your character's arc, goals, and motivations to ensure a
                satisfying and meaningful character journey that resonates with
                readers.
            </p>
        </div>

        <achievement-progress-circle :percent="card.percent" />
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementProgressCircle from '@/components/AchievementProgressCircle.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    showStoryName: { type: Boolean, default: true },
})
</script>

<style scoped></style>
