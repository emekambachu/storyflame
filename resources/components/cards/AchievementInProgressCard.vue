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

        <achievement-progress-circle
            :percent="card.percent"
            :color="card.color"
            :time="card?.time"
            :icon="card?.icon?.path"
        />
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementProgressCircle from '@/components/AchievementProgressCircle.vue'
import { hexToRgba } from '@/utils/colorUtils'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    showStoryName: { type: Boolean, default: true },
})

</script>

<style scoped></style>
