<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section class="!gap-6 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    title="Goals"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex flex-col gap-2">
                <h6 class="text-sm font-semibold text-neutral-300">
                    Inspired by
                </h6>
                <div class="flex items-center gap-3">
                    <image-component
                        v-for="(goal, goalID) in story.goals"
                        :key="goalID"
                        :src="goal?.image?.path"
                        class="h-40 w-[108px] rounded-lg"
                    />
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <h6 class="text-sm font-semibold text-neutral-300">
                    Goals for the Series
                </h6>
                <p class="text-sm font-normal text-zinc-800">
                    {{ story?.goals?.goals_for_series }}
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <h6 class="text-sm font-semibold text-neutral-300">
                    Promises to the Audience
                </h6>
                <p class="text-sm font-normal text-zinc-800">
                    {{ story?.goals?.promises_to_audience }}
                </p>
            </div>
        </title-section>
        <title-section
            v-if="story?.target_audience"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Target audience"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <template v-if="story?.target_audience">
                <target-audience-card
                    v-for="(audience, audienceID) in story?.target_audience"
                    :key="audienceID"
                    :card="audience"
                />
            </template>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import TargetAudienceCard from '@/components/cards/TargetAudienceCard.vue'

import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import ImageComponent from '@/components/ImageComponent.vue'

import PointIcon from '@/components/icons/PointIcon.vue'
import { Story } from '@/types/story'

const story = {
    target_audience: [
        {
            title: 'Fantasy Enthusiast',
            description:
                'Immersed in fantasy worlds, interested in medieval history, politics, and warfare.',
            prefer: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
            dislike:
                'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
        },
        {
            title: 'Fantasy Enthusiast',
            description:
                'Immersed in fantasy worlds, interested in medieval history, politics, and warfare.',
            prefer: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
            dislike:
                'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
        },
    ],
    goals: {
        inspired: [
            {
                image: {
                    path: 'https://placekeanu.com/600/400',
                },
            },
            {
                image: {
                    path: 'https://placekeanu.com/600/400',
                },
            },
            {
                image: {
                    path: 'https://placekeanu.com/600/400',
                },
            },
        ],
        goals_for_series:
            'To become a staff writer for House of the Dragon and create a Sci-Fi Novel Series',
        promises_to_audience:
            'To become a staff writer for House of the Dragon and create a Sci-Fi Novel Series',
    },
}
</script>

<style scoped></style>
