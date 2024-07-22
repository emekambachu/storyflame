<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 pt-2">
        <div
            v-if="progress?.length || progressDescription"
            class="flex flex-col bg-white"
        >
            <title-section
                v-if="progress"
                class="px-3 py-3"
            >
                <template #title>
                    <title-with-link
                        button-text="All Reports"
                        class="!p-0"
                        title="Progress Report"
                        title-class="text-lg text-stone-800 font-bold"
                    />
                </template>
                <div class="flex w-full flex-wrap gap-2">
                    <progress-card
                        v-for="(item, key) in progress"
                        :key="key"
                        :item="item"
                        :title="key"
                    />
                </div>
            </title-section>

            <title-section
                v-if="progressDescription"
                class="px-3 py-4"
                title="Current Progress"
                title-class="text-lg font-bold text-zinc-800"
            >
                <p
                    v-if="progressDescription"
                    class="rounded-lg border border-stone-200 bg-stone-100 p-2 font-main text-sm text-stone-800"
                >
                    {{ progressDescription }}
                </p>
            </title-section>
        </div>

        <title-section
            v-if="continueAchievements?.length"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    class="!p-0"
                    title="Continue where you left off"
                    title-class="text-lg text-stone-800 font-bold"
                />
            </template>

            <achievement-in-progress-card
                v-for="achievement in (continueAchievements
                    ?.slice()
                    .sort((a, b) => b.percent - a.percent)
                    .slice(0, 3) || [])"
                :key="achievement.id"
                :card="achievement"
            />
        </title-section>

        <title-section
            v-if="upNextAchievements?.length"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    class="!p-0"
                    title="Start something new"
                    title-class="text-lg text-stone-800 font-bold"
                />
            </template>

            <achievement-in-progress-card
                v-for="achievement in (upNextAchievements
                    ?.slice()
                    .sort((a, b) => b.percent - a.percent)
                    .slice(0, 3) || [])"
                :key="achievement.id"
                :card="achievement"
            />
        </title-section>

        <title-section
            v-if="discrepancies?.length"
            class="!gap-6 px-4 py-6"
            style="
                background: linear-gradient(
                        180deg,
                        rgba(0, 0, 0, 0) 0%,
                        #000 100%
                    ),
                    linear-gradient(
                        0deg,
                        rgba(0, 0, 0, 0.3) 0%,
                        rgba(0, 0, 0, 0.3) 100%
                    ),
                    linear-gradient(0deg, #404040 0%, #404040 100%), #fff;
            "
        >
            <template #title>
                <title-with-link
                    class="!p-0"
                    title="Clarify discrepancies"
                    title-class="text-lg text-white font-bold"
                />
            </template>

            <discrepancies-card
                v-for="(card, cardID) in discrepancies"
                :key="cardID"
                :card="card"
            />
        </title-section>

        <title-section class="!gap-6 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    class="!p-0"
                    title="Delve into something new"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <start-something-new v-if="startSmthNew" />
        </title-section>
    </div>
</template>

<script lang="ts" setup>
import { PropType } from 'vue'

import ProgressCard from '@/components/cards/ProgressCard.vue'
import StartSomethingNew from '@/components/StartSomethingNew.vue'
import DiscrepanciesCard from '@/components/cards/DiscrepanciesCard.vue'
import AchievementInProgressCard from '@/components/cards/AchievementInProgressCard.vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import { Achievement } from '@/types/achievement'
import { ElementProgress } from '@/types/elements'

const props = defineProps({
    progress: {
        type: Object as PropType<Record<string, ElementProgress>>,
        required: true,
    },
    progressDescription: {
        type: String,
        default: () => undefined,
    },
    continueAchievements: {
        type: Array as PropType<Achievement[]>,
        default: () => [],
    },
    upNextAchievements: {
        type: Array as PropType<Achievement[]>,
        default: () => [],
    },
    discrepancies: {
        type: Array,
        default: () => [],
    },
    startSmthNew: {
        type: Boolean,
        default: false,
    },
})
</script>

<style scoped></style>
