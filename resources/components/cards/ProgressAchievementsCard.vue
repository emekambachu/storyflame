<template>
    <div
        class="flex items-center justify-between gap-3 p-3"
        :style="backgroundColorStyle"
    >
        <div class="flex w-full flex-col gap-1">
            <h3 class="text-[22px] font-bold leading-[140%] text-white">
                {{ item.title }}
            </h3>

            <achievements-list-card
                :achievements="item?.achievements"
                :color="achievementsColorClass"
            />
        </div>

        <flame-icon
            v-if="item?.progress"
            :priority="item.progress"
            class="!h-8 !w-8"
            flameClass="w-6 h-6"
        />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import FlameIcon from '@/components/icons/FlameIcon.vue'
import AchievementsListCard from '@/components/cards/AchievementsListCard.vue'

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})

const backgroundColorStyle = computed(() => {
    const progress = props.item.progress
    if (progress > 90)
        return 'background: linear-gradient(142deg, rgba(0, 0, 0, 0.00) 25.26%, rgba(0, 0, 0, 0.20) 82.93%), #FF470D;background-blend-mode: plus-darker, normal;'
    if (progress > 60)
        return 'background: linear-gradient(142deg, rgba(0, 0, 0, 0.00) 25.26%, rgba(0, 0, 0, 0.20) 82.93%), #FF9202'
    if (progress > 30)
        return 'background: linear-gradient(142deg, rgba(246, 192, 0, 0.20) 25.26%, rgba(167, 131, 0, 0.20) 82.93%), #D8A800;'
    return 'background: linear-gradient(142deg, rgba(0, 0, 0, 0.00) 25.26%, rgba(0, 0, 0, 0.20) 82.93%), #A9B1BB; background-blend-mode: plus-darker, normal;'
})

const achievementsColorClass = computed(() => {
    const progress = props.item.progress
    if (progress > 90) return '#ED5933'
    if (progress > 60) return '#F09933'
    if (progress > 30) return '#DEB834'
    return '#A5ABB3'
})
</script>

<style scoped></style>
