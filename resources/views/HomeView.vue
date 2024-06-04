<template>
    <div class="w-full min-h-dvh flex flex-col items-center">
        <div class="w-full px-4 py-8 flex flex-col gap-3 bg-neutral-950">
            <div class="w-full flex items-center justify-between">
                <logo-icon />

                <div class="w-8 h-8 shrink-0 rounded-full bg-white"></div>
            </div>

            <p class="font-medium text-sm text-slate-400 uppercase">
                {{ data.smth }}
            </p>

            <div class="flex items-center justify-between gap-3">
                <achievement-summary-card
                    value-class="text-sm text-pure-white font-bold"
                    card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"
                    v-for="(card, cardID) in data.achievements_summary"
                    :key="cardID"
                    :item="card"
                />
            </div>
        </div>
        <div
            v-if="showDiscuss"
            class="px-4 pb-3 fixed bottom-0 right-0 left-0"
        >
            <discuss-component @close="showDiscuss = false" />
        </div>
        <tab-layout
            class="w-full !px-0 mt-5 !gap-0"
            tabs-content-class="w-full flex flex-col gap-2 bg-slate-100"
            tab-content-class="pb-6 w-full bg-white"
            :scrollToPageSection="true"
            :tabs="[
                { title: 'Progress', template: 'stories' },
                { title: 'Continue', template: 'continue' },
                { title: 'Clarify', template: 'clarify' },
                { title: 'New', template: 'new' },
                { title: 'Transcripts', template: 'transcripts' },
            ]"
        >
            <template #stories>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <h4 class="text-lg text-black font-bold">
                            Recent Stories
                        </h4>
                    </template>
                    <div class="max-w-full w-full overflow-scroll flex gap-4">
                        <recent-story-card
                            v-for="(story, storyID) in data.stories"
                            :key="storyID"
                            :story="story"
                        />
                        <new-story-card class="h-24" />
                    </div>
                </title-section>
            </template>
            <template #continue>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <title-with-link
                            title="Continue where you left off"
                            class="!p-0"
                            title-class="text-lg text-black font-bold"
                        />
                    </template>
                    <character-creation-card
                        v-for="(character, characterID) in data.characters"
                        :key="characterID"
                        :card="character"
                    />
                </title-section>
            </template>
            <template #clarify>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <title-with-link
                            title="Clarify discrepancies"
                            class="!p-0"
                            title-class="text-lg text-black font-bold"
                        />
                    </template>

                    <character-creation-card
                        v-for="(character, characterID) in data.characters"
                        :key="characterID"
                        :card="character"
                    />
                </title-section>
            </template>

            <template #new>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <title-with-link
                            title="Starting Something New"
                            class="!p-0"
                            title-class="text-lg text-black font-bold"
                        />
                        <div class="flex items-center gap-3 w-full">
                            <div
                                v-for="(item, itemID) in createNew"
                                :key="itemID"
                                class="rounded-lg bg-stone-100 p-2 flex flex-col items-center text-center gap-1 w-full"
                            >
                                <span
                                    class="text-stone-400 text-xs font-normal"
                                >
                                    New
                                </span>
                                <span
                                    class="text-stone-600 text-xs font-semibold"
                                >
                                    {{ item }}
                                </span>
                            </div>
                        </div>
                        <hr class="text-stone-300 w-48 mx-auto my-1" />
                        <character-creation-card
                            v-for="(character, characterID) in data.characters"
                            :key="characterID"
                            :card="character"
                        />
                    </template>
                </title-section>
            </template>
            <template #transcripts>
                <home-statistic-component
                    :statistic="data.statistic"
                    class="!py-6"
                />
            </template>
        </tab-layout>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
// import { useAuthStore } from '@/stores/auth'
import LogoIcon from '@/components/icons/LogoIcon.vue'
import NewStoryCard from '@/components/cards/NewStoryCard.vue'
import RecentStoryCard from '@/components/cards/RecentStoryCard.vue'

import TabLayout from '@/components/TabLayout.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import DiscussComponent from '@/components/DiscussComponent.vue'
import CharacterCreationCard from '@/components/cards/CharacterCreationCard.vue'
import HomeStatisticComponent from '@/components/HomeStatisticComponent.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'

// const appName = import.meta.env.VITE_APP_NAME

// const { logout, isLoggedIn } = useAuthStore()

const showDiscuss = ref(true)

const createNew = ['Story', 'Character', 'Sequence']
const data = {
    smth: '[something celebratory]',
    stories: [
        {
            title: 'Game of Thrones',
            episode: 'EP 101: Pilot',
            poster: {
                path: 'https://picsum.photos/900',
            },
        },
        {
            title: 'Game of Thrones',
            episode: 'EP 101: Pilot',
            poster: {
                path: 'https://picsum.photos/900',
            },
        },
    ],
    statistic: [
        {
            title: 'Your Bio',
            description:
                'Veronica is a rising star in the world of dramatic television writing. She recently landed a coveted position as Writer Coordinator on the acclaimed series "For All Mankind", where she will hорe the opportunity to further hone her skills in crafting compelling character-driven narratives.',
        },
        {
            title: 'Your Writing Goals',
            description:
                'To become a staff writer for House of the Dragon and create a Sci-Fi Novel Series',
        },
        {
            title: 'Your Inspiration',
            inspiration: [
                {
                    poster: 'https://picsum.photos/900',
                },
                {
                    poster: 'https://picsum.photos/900',
                },
                {
                    poster: 'https://picsum.photos/900',
                },
                {
                    poster: 'https://picsum.photos/900',
                },
            ],
        },
    ],
    achievements: [
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Two',
            completed_at: null,
            progress: 50,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Three',
            completed_at: null,
            progress: 0,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Four',
            completed_at: null,
            progress: 75,
        },
    ],
    achievements_summary: [
        { title: 'Earned', value: 10 },
        { title: 'progress', value: 0 },
        { title: 'Up to next', value: 0 },
    ],

    characters: [
        {
            percent: 20,
            story: 'Game of thrones',
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
        {
            percent: 80,
            story: 'Game of thrones',
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
    ],
}
</script>
