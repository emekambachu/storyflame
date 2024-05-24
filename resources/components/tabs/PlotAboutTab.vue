<template>
    <div class="w-full px-4 flex flex-col gap-8">
        <title-section>
            <template #title>
                <title-with-link
                    title="Achievements"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <div class="flex items-center gap-3 justify-between w-full">
                <achievement-summary-card
                    v-for="(item, itemID) in data.achievements_summary"
                    :key="itemID"
                    :item="item"
                />
            </div>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Breaking Point</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.breaking_point }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Main protagonist</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.main_protagonist }}</p>
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Sequences"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <div class="flex flex-col gap-2">
                <sequence-card
                    v-for="(goal, goalID) in data.goals"
                    :key="goalID"
                    :card="goal"
                />
            </div>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Themes</h4>
            </template>
            <theme-card
                v-for="(theme, themeID) in data.themes"
                :key="themeID"
                :card="theme"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import SequenceCard from '@/components/cards/SequenceCard.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
