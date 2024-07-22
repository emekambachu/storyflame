<template>
    <router-link
        :style="`
            background: linear-gradient(
                    0deg,
                    ${hexToRgba(card.color)} 0%,
                    ${hexToRgba(card.color)} 100%
                ),
                #fff;`"
        :to="{
            name: 'story-develop-achievement',
            params: { achievement: card.id },
        }"
        class="flex w-full items-start gap-4 rounded-lg p-4"
    >
        <div class="flex w-full flex-col items-start gap-1">
            <h5 class="text-base font-semibold text-gray-800">
                {{ card.title }}
            </h5>
            <p
                v-if="card?.subtitle || card?.story"
                :style="`color: ${card?.color};`"
                class="flex items-center gap-1 text-xs font-semibold"
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
            :color="card.color"
            :icon="card?.icon?.path"
            :percent="card.percent"
            :time="(card?.time / 60).toFixed(0) + ' min'"
        />
    </router-link>
</template>

<script lang="ts" setup>
import PointIcon from '@/components/icons/PointIcon.vue'
import AchievementProgressCircle from '@/components/AchievementProgressCircle.vue'
import { hexToRgba } from '@/utils/colorUtils'
import { PropType } from 'vue'
import { Achievement } from '@/types/achievement'

const props = defineProps({
    card: {
        type: Object as PropType<Achievement>,
        required: true,
    },
    showStoryName: { type: Boolean, default: true },
})
</script>

<style scoped></style>
