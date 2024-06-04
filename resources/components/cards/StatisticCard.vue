<template>
    <div
        class="w-full flex flex-col gap-4 rounded-t-lg p-4"
        style="background: linear-gradient(153deg, #ff6320 0%, #df2800 83.33%)"
    >
        <div class="w-full flex items-center justify-between">
            <h2 class="font-bold text-base text-white">Last session</h2>
            <span class="font-normal text-xs text-white">
                {{ data.last_session }}
            </span>
        </div>
        <div class="flex items-start justify-between w-full">
            <div
                v-for="(item, itemID) in itemsArray"
                :key="itemID"
                class="flex flex-col items-center w-full max-w-20"
            >
                <div
                    class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center"
                    style="background: rgba(255, 255, 255, 0.3)"
                >
                    <component
                        :is="item.icon"
                        class="text-white"
                    />
                </div>
                <h6 class="text-base text-white font-bold mt-2">
                    {{ item.value }}
                </h6>
                <span class="text-red-300 text-xs font-semibold capitalize">
                    {{ item.title }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import ClockIcon from '@/components/icons/ClockIcon.vue'
import FlashIcon from '@/components/icons/FlashIcon.vue'
import AchievementFrame from '@/components/icons/AchievementFrame.vue'

const data = {
    last_session: 'Just now',
    session: {
        time: 10,
        elements: 14,
        achievements: 3,
    },
}

const itemsArray = [
    {
        title: 'Minutes',
        value: data?.session?.time ?? 0,
        icon: ClockIcon,
    },
    {
        title: 'elements',
        value: data?.session?.elements ?? 0,
        icon: FlashIcon,
    },
    {
        title: 'achievements',
        value: data?.session?.achievements ?? 0,
        icon: AchievementFrame,
    },
]
</script>

<style scoped></style>
