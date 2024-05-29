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
            class="w-full !px-0 mt-5"
            :tabs="[
                { title: 'Progress', template: 'progress' },
                { title: 'Clarify', template: 'clarify' },
                { title: 'Continue', template: 'continue' },
                { title: 'Finish', template: 'finish' },
                { title: 'Transcripts from yesterday', template: 'transcipts' },
            ]"
        >
            <template #progress>
                <div class="w-full flex flex-col gap-2 bg-slate-200">
                    <div class="px-4 pb-6 w-full bg-white">
                        <div
                            class="px-2 py-5 w-full gap-6 rounded bg-slate-200 border border-slate-300 flex flex-col"
                        >
                            <achievements-statistic-card />

                            <title-section
                                v-for="(item, itemID) in data.works_on"
                                :key="itemID"
                                class="!gap-2"
                            >
                                <template #title>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm text-gray-500 font-semibold"
                                        >
                                            {{ item.story }}
                                        </span>
                                        <h4
                                            class="text-base text-gray-800 font-semibold"
                                        >
                                            {{ item.title }}
                                        </h4>
                                    </div>
                                </template>

                                <p class="text-xs text-gray-500 font-normal">
                                    {{ item.description }}
                                </p>
                            </title-section>
                        </div>
                    </div>

                    <div class="px-4 py-6 w-full bg-white">
                        <title-section class="!gap-6">
                            <template #title>
                                <title-with-link
                                    title="Clarify some discrepancies"
                                    class="!p-0"
                                    title-class="text-lg text-black font-bold"
                                />
                            </template>

                            <character-creation-card
                                v-for="(
                                    character, characterID
                                ) in data.characters"
                                :key="characterID"
                                :card="character"
                            />
                        </title-section>
                    </div>

                    <div class="px-4 py-6 w-full bg-white">
                        <title-section class="!gap-6">
                            <template #title>
                                <title-with-link
                                    title="Continue where you left off"
                                    class="!p-0"
                                    title-class="text-lg text-black font-bold"
                                />
                            </template>
                        </title-section>
                    </div>

                    <div class="px-4 py-6 w-full bg-white">
                        <title-section class="!gap-6">
                            <template #title>
                                <title-with-link
                                    title="Transcripts from yesterday"
                                    class="!p-0"
                                    title-class="text-lg text-black font-bold"
                                />
                            </template>
                        </title-section>
                    </div>
                </div>
            </template>
            <template #clarify>
                <div>Nothing here, please check tab later</div>
            </template>
            <template #continue>
                <div>Nothing here, please check tab later</div>
            </template>
            <template #finish></template>
        </tab-layout>
        <!-- <h1 class="text-2xl">
            Welcome to
            <span class="text-gradient font-bold">
                {{ appName }}
            </span>
        </h1> -->
        <!-- <div class="flex flex-col gap-2">
            <router-link
                is="btn"
                :to="{ name: 'login' }"
                class="bg-red-500 text-white px-3 py-1 rounded-full"
            >
                Get Started
            </router-link>
            <button
                v-if="isLoggedIn"
                class="text-gray-500 px-3 py-1 rounded-full"
                @click="logout"
            >
                logout
            </button>
        </div> -->
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import LogoIcon from '@/components/icons/LogoIcon.vue'

import TabLayout from '@/components/TabLayout.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import DiscussComponent from '@/components/DiscussComponent.vue'
import CharacterCreationCard from '@/components/cards/CharacterCreationCard.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'
import AchievementsStatisticCard from '@/components/cards/AchievementsStatisticCard.vue'

const appName = import.meta.env.VITE_APP_NAME

const { logout, isLoggedIn } = useAuthStore()

const showDiscuss = ref(true)

const data = {
    smth: '[something celebratory]',
    works_on: [
        {
            story: 'Game of Thrones',
            title: 'Character Development',
            description:
                'You made significant strides in fleshing out the backstory and motivation of your protagonist, Lila. Through the "Uncovering Character Motivation" achievement, you discovered that Lila`s driving force stems from a childhood tragedy that shattered her trust in others. This insight sparked ideas for her character arc, setting the stage for a powerful journey of healing and redemption. You also completed the "Defining Character Flaws" achievement, identifying Lila`s struggle with vulnerability and her tendency to push others away. These flaws create compelling opportunities for growth and conflict throughout the story.',
        },
        {
            story: 'Game of Thrones',
            title: 'Plot and Theme Progress',
            description:
                'Your work on character development laid the foundation for major plot and theme breakthroughs. In the "Crafting the Inciting Incident" achievement, you brainstormed a gripping scene where Lila`s past trauma resurfaces, forcing her to confront her deepest fears. This inciting incident propels her into a high-stakes adventure that challenges her to overcome her flaws and forge unexpected alliances. You also made headway in the "Establishing the Central Theme" achievement, connecting Lila`s personal struggle with the universal theme of the power of empathy and forgiveness. This theme will resonate throughout the story, adding depth and meaning to Lila`s journey.',
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
