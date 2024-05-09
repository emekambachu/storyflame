<template>
    <div
        class="flex flex-col justify-between items-center text-center w-full max-w-[90px] shrink-0"
        @click="show(AchievementPopup, item)"
    >
        <img
            v-if="item?.icon"
            :class="{
                brightness: !item.achievement_date && item.progress !== 100,
            }"
            :src="item.icon"
            alt="icon"
            class="h-[89px] w-[82px] object-contain"
        />

        <h6 class="text-black text-sm font-bold">{{ item.title }}</h6>
        <p
            v-if="item.achievement_date && item.progress == 100"
            class="text-black text-sm font-normal opacity-50"
        >
            {{ item.achievement_date }}
        </p>
        <div
            v-else
            class="w-full flex items-center gap-1 mt-1"
        >
            <progress-bar
                :percent="item.progress"
                class="w-full !h-[6px]"
            />
            <span class="text-[8px] text-gray-700 font-normal">
                {{ item.progress }}%
            </span>
        </div>
    </div>
</template>

<script lang="ts" setup>
import ProgressBar from '@/components/ProgressBar.vue'
import { inject } from 'vue'
import AchievementPopup from '@/components/modals/AchievementPopup.vue'

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})

const { show } = inject('modal')
</script>

<style lang="css" scoped>
.brightness {
    filter: brightness(0.8) contrast(0.3);
}
</style>
