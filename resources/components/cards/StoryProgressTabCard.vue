<template>
    <div class="flex flex-col gap-4 bg-white pb-4">
        <progress-achievements-card :item="card" />
        <title-section class="!gap-2 px-3">
            <template #title>
                <title-with-link
                    title="Writing Goal effectiveness"
                    class="!p-0"
                    title-class="text-sm text-stone-600 font-bold"
                />
            </template>
            <p class="text-sm font-normal text-stone-800">
                {{ card.effectiveness }}
            </p>
        </title-section>
        <title-section class="!gap-2 px-3">
            <template #title>
                <title-with-link
                    title="For Target Audience"
                    class="!p-0"
                    title-class="text-sm text-stone-600 font-bold"
                />
            </template>
            <p class="text-sm font-normal text-stone-800">
                {{ card.audience }}
            </p>
        </title-section>
        <title-section class="!gap-2 px-3">
            <template #title>
                <title-with-link
                    title="Market Comparisons"
                    class="!p-0"
                    title-class="text-sm text-stone-600 font-bold"
                />
            </template>
            <p class="text-sm font-normal text-stone-800">
                {{ card.market_comparisons }}
            </p>
        </title-section>

        <title-section class="!gap-2 px-3 py-4">
            <template #title>
                <title-with-link
                    title="Potential Explorations"
                    class="!p-0"
                    title-class="text-sm text-stone-600 font-bold"
                />
            </template>
            <template v-if="card?.potential_explorations">
                <achievement-in-progress-card
                    v-for="(
                        achievement, achievementID
                    ) in card.potential_explorations"
                    :key="achievementID"
                    :card="achievement"
                />
            </template>
        </title-section>
        <title-section class="!gap-2 px-3 pb-4">
            <template #title>
                <title-with-link
                    title="Delve into something new"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <template v-if="card?.something_new">
                <achievement-in-progress-card
                    v-for="(achievement, achievementID) in card.something_new"
                    :key="achievementID"
                    :card="achievement"
                />
            </template>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import ProgressAchievementsCard from '@/components/cards/ProgressAchievementsCard.vue'
import AchievementInProgressCard from '@/components/cards/AchievementInProgressCard.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
