<template>
    <div
        class="flex items-center justify-between gap-3 p-3"
        :class="[
            {
                'bg-red-600': item.readiness == 1,
                'bg-amber-500': item.readiness == 2,
                'bg-yellow-400': item.readiness == 3,
                'bg-slate-400': item.readiness == 4,
            },
        ]"
    >
        <div class="flex w-full flex-col gap-1">
            <h3 class="text-[22px] font-bold leading-[140%] text-white">
                {{ item.title }}
            </h3>

            <achievements-list-card :achievements="item?.achievements" />
        </div>

        <flame-icon
            v-if="item?.readiness"
            :priority="item.readiness"
            class="!h-8 !w-8"
            flameClass="w-6 h-6"
        />
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/icons/FlameIcon.vue'
import AchievementPlaceholderIcon from '@/components/icons/AchievementPlaceholderIcon.vue'
import AchievementsListCard from '@/components/cards/AchievementsListCard.vue'

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
