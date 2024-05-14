<template>
    <div class="pb-6 flex flex-col gap-6">
        <div
            class="pt-6 flex flex-col gap-8 w-full"
            :class="story?.image?.path ? 'pb-4' : ''"
            :style="`background: ${
                story?.image?.path
                    ? `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url(${story.image.path}) lightgray 50% / cover no-repeat;`
                    : 'transparent'
            }`"
        >
            <button
                class="flex items-center gap-2 px-4"
                :class="story?.image?.path ? 'text-white' : 'text-black'"
            >
                <chevron-icon />
                <span class="text-lg font-normal">Back</span>
            </button>

            <div class="flex flex-col gap-1.5 px-4">
                <span
                    class="text-sm font-normal opacity-70"
                    :class="story?.image?.path ? 'text-white' : 'text-black'"
                >
                    {{ story.genre }}
                </span>

                <div class="flex items-center w-full justify-between">
                    <h1
                        class="font-bold text-3xl"
                        :class="
                            story?.image?.path ? 'text-white' : 'text-black'
                        "
                    >
                        {{ story.name }}
                    </h1>

                    <div class="w-10 h-10 shrink-0">
                        <progress-bar-circle
                            :percent="story.percent"
                            class="w-full"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span
                        v-for="(genre, genreID) in story.genres"
                        :key="genreID"
                        :class="
                            story?.image?.path ? 'text-white' : 'text-black'
                        "
                        class="text-sm font-normal opacity-40 gap-1.5 flex items-center"
                    >
                        <span v-if="genreID !== 0"><point-icon /></span>
                        {{ genre }}
                    </span>
                </div>

                <p
                    class="text-sm opacity-70"
                    :class="story?.image?.path ? 'text-white' : 'text-black'"
                >
                    {{ story.description }}
                </p>
            </div>
        </div>

        <div class="flex items-center relative">
            <div
                v-for="(tab, tabID) in tabs"
                :key="tabID"
                :class="
                    tabID == selectedTab
                        ? 'text-red-600 border-red-600'
                        : 'text-neutral-500 border-neutral-300'
                "
                class="mx-4 text-base font-normal border-b-2 relative z-10"
                @click="selectedTab = tabID"
            >
                {{ tab }}
            </div>
            <div class="w-full h-[2px] bg-neutral-300 absolute bottom-0 z-5" />
        </div>

        <story-about-tab
            v-if="selectedTab == 0"
            :story="story"
        />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import StoryAboutTab from '@/components/StoryAboutTab.vue'
import ProgressBarCircle from '@/components/ProgressBarCircle.vue'

import PointIcon from '@/components/icons/PointIcon.vue'
import ChevronIcon from '@/components/icons/ChevronIcon.vue'

const story = {
    image: { path: 'https://picsum.photos/900' },
    name: 'Game of Thrones',
    genre: 'TV Show, MA-16',
    percent: 80,
    genres: ['Fantasy', 'Drama', 'Adventure', 'Political Intrigue'],
    description:
        'In a world where summers span decades and winters can last a lifetime, the noble houses of Westeros battle for control of the Seven Kingdoms, while an ancient enemy awakens in the North, threatening the realm with destruction.',
    goals: [
        'Explore a Unique Setting',
        'Develop Complex Character Arcs',
        'Craft a Cliffhanger Ending',
    ],
    market_comps: [
        { image: { path: 'https://picsum.photos/300' } },
        { image: null },
        { image: { path: 'https://picsum.photos/600' } },
    ],

    characters: [
        {
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
        {
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
    ],
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
    impactful_scenes: [
        {
            id: '1',
            priority: 1,
            name: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
        },
        {
            id: '21',
            priority: 2,
            name: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
        },
    ],
}

const tabs = ['About', 'Progress', 'Elements', 'Drafts']

const selectedTab = ref(0)
</script>

<style scoped></style>
