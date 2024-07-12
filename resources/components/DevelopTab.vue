<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 pt-2">
        <div class="flex flex-col bg-white">
            <title-section
                v-if="data?.progress_list?.length"
                class="px-3 py-3"
            >
                <template #title>
                    <title-with-link
                        class="!p-0"
                        title="Progress Report"
                        title-class="text-lg text-stone-800 font-bold"
                        button-text="All Reports"
                    />
                </template>
                <div class="flex w-full flex-wrap gap-2">
                    <progress-card
                        v-for="(item, itemID) in data?.progress_list"
                        :key="itemID"
                        :item="item"
                    />
                </div>
            </title-section>
            <title-section
                class="px-3 py-4"
                title="Current Progress"
                title-class="text-lg font-bold text-zinc-800"
            >
                <p
                    v-if="data?.progress_description"
                    class="rounded-lg border border-stone-200 bg-stone-100 p-2 font-main text-sm text-stone-800"
                >
                    {{ data.progress_description }}
                </p>
            </title-section>
        </div>
        <title-section class="!gap-6 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    title="Continue where you left off"
                    class="!p-0"
                    title-class="text-lg text-stone-800 font-bold"
                />
            </template>

            <template v-if="data?.achievements_in_progress">
                <achievement-in-progress-card
                    v-for="(
                        achievement, achievementID
                    ) in data.achievements_in_progress"
                    :key="achievementID"
                    :card="achievement"
                />
            </template>
        </title-section>

        <title-section
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
                    title="Clarify discrepancies"
                    class="!p-0"
                    title-class="text-lg text-white font-bold"
                />
            </template>

            <discrepancies-card
                v-for="(card, cardID) in data?.discrepancies"
                :key="cardID"
                :card="card"
            />
        </title-section>

        <title-section class="!gap-6 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    title="Delve into something new"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <start-something-new v-if="startSmthNew" />
            <template v-if="data?.achievements_in_progress">
                <achievement-in-progress-card
                    v-for="(
                        achievement, achievementID
                    ) in data.achievements_in_progress"
                    :key="achievementID"
                    :card="achievement"
                />
            </template>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

import ProgressCard from '@/components/cards/ProgressCard.vue'
import StartSomethingNew from '@/components/StartSomethingNew.vue'
import DiscrepanciesCard from '@/components/cards/DiscrepanciesCard.vue'
import AchievementInProgressCard from '@/components/cards/AchievementInProgressCard.vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    startSmthNew: {
        type: Boolean,
        default: false,
    },
})
</script>

<style scoped></style>
