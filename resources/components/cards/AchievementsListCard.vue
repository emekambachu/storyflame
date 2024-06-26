<template>
    <div class="flex items-center">
        <achievement-placeholder
            v-for="(achievement, achievementID) in achievements"
            :key="achievementID"
            :icon="achievement?.progress == 100 ? achievement?.icon : null"
            :color="color"
            class="h-5 w-[18px]"
            :class="[
                achievement?.progress !== 100 ? '' : 'z-20',
                achievementID > 0 ? '-mr-1.5' : '',
            ]"
        />
    </div>
</template>

<script setup lang="ts">
import AchievementPlaceholder from '@/components/icons/AchievementPlaceholder.vue'
const props = defineProps({
    achievements: {
        type: Array,
        required: true,
    },
    color: {
        type: String,
        default: '#363432',
    },
})
</script>

<style scoped></style>
