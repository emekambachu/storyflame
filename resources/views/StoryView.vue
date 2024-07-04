<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-6 pb-6">
            <!--
                animate-translate-y="190px"
                 -->
            <tab-layout
                :tabs="[
                    {
                        title: 'Develop',
                        template: 'develop',
                    },
                    {
                        title: 'Progress',
                        template: 'progress',
                    },
                    {
                        title: 'Arcs',
                        template: 'arcs',
                    },
                    {
                        title: 'Elements',
                        template: 'elements',
                    },
                    {
                        title: 'References',
                        template: 'references',
                    },
                ]"
                class="!gap-0"
                header-height="400"
                collapse-header-height="200"
                menu-btn-class="w-full text-base text-stone-500 bg-stone-100 px-3 py-2 rounded-lg border-none"
                menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-3 py-2 border-none"
                menu-container-class="w-full flex gap-2"
                menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3 bg-white"
            >
                <!--                <story-header :story="story" />-->
                <header-animated
                    collapse-header-height="80"
                    header-height="478"
                >
                    <!--                    <default-element-header height="478px" />-->
                    <story-header
                        v-if="story"
                        :story="story"
                    />
                    <template #sticky>
                        <tab-layout-tabs />
                    </template>
                </header-animated>
                <template #develop>
                    <develop-tab
                        :data="story"
                        startSmthNew
                    />
                </template>
                <template #progress>
                    <story-progress-tab :story="story" />
                </template>
                <template #arcs>
                    <story-story-tab :story="story" />
                </template>
                <template #elements>
                    <story-elements-tab :story="story" />
                </template>
                <template #references>
                    <story-marketing-tab :story="story" />
                </template>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import StoryStoryTab from '@/components/StoryStoryTab.vue'
import DevelopTab from '@/components/DevelopTab.vue'
import StoryProgressTab from '@/components/StoryProgressTab.vue'
import StoryElementsTab from '@/components/StoryElementsTab.vue'
import StoryMarketingTab from '@/components/StoryMarketingTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import { useRoute } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { getStory } from '@/utils/endpoints'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import StoryHeader from '@/components/StoryHeader.vue'

const route = useRoute()
// const storyId = computed(() => route.params.story)

// const { data: story } = useQuery({
//     queryKey: ['story', storyId.value],
//     queryFn: () => getStory(storyId.value),
//     select(data) {
//         return data.data
//     },
// })

const story = {
    id: 'test',
    name: 'Test story',
    image: {
        path: 'https://placekeanu.com/600/400',
    },
    description:
        'This is a test story about some hero saving the world from some evil villain. First he struggles, then he realizes someting and then he wins. The end.',
    type: 'Movie',
    genres: ['Action', 'Comedy'],
    format: ['Show', 'MA-16'],
    progress: 60,
    progress_description:
        'Your characters and themes are looking great, but your story could use some world building development.',
    progress_list: [
        {
            name: 'Characters',
            progress: 30,
            count: 20,
        },
        {
            name: 'Sequences',
            progress: 60,
            count: 20,
        },
        {
            name: 'Themes',
            progress: 100,
            count: 20,
        },
        {
            name: 'Appeal',
            progress: 10,
            count: 20,
        },
    ],
    goals: [
        'Create a main hero',
        'Create a villain',
        'Create a sidekick',
        'Create a world',
    ],
    market_comps: [
        {
            title: 'The Incredibles',
            image: {
                path: 'https://placekeanu.com/450',
            },
        },
        {
            title: 'Despicable Me',
            image: {
                path: 'https://placekeanu.com/300',
            },
        },
        {
            title: 'Despicable Me',
            image: null,
        },
    ],

    characters: [
        {
            story: 'Game of Thrones',
            episode: 'Ep: sldnfjs sdlkflsdjk skfdn',
            role: 'Protagonist',
            title: 'Daenerys Targaryen',
            type: 'Helpless Sibling',
            description: 'Gain independence and reclaim her ancestral throne.',
        },
        {
            story: 'Game of Thrones',
            episode: 'Ep: sldnfjs sdlkflsdjk skfdn',
            role: 'Protagonist',
            title: 'Daenerys Targaryen',
            type: 'Helpless Sibling',
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
    drafts: [],
    discrepancies: [
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
    ],
    achievements: [
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            id: 2,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            id: 3,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 4,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
    ],
    achievements_not_started: [
        {
            icon: {
                path: 'https://picsum.photos/90',
            },
            type: 'Character development',
            time: '5 min',
            percent: 0,
            color: '#A516AD',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],
    achievements_in_progress: [
        {
            icon: {
                path: 'https://picsum.photos/90',
            },
            type: 'Character development',
            time: '5 min',
            percent: 20,
            color: '#A516AD',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],
    achievements_completed: [
        {
            icon: {
                path: 'https://picsum.photos/900',
            },
            date: '25 Apr',
            percent: 20,
            color: '#099AB1',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],

    plot: {
        title: 'Plot',
        progress: 30,
        achievements: [
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: '2023-05-10',
                progress: 100,
            },
            {
                id: 2,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: '2023-05-10',
                progress: 100,
            },
            {
                id: 3,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 4,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
            {
                id: 1,
                icon: 'https://picsum.photos/900',
                title: 'Achievement One',
                completed_at: null,
                progress: 0,
            },
        ],

const { data: story } = useQuery({
    queryKey: ['story', storyId.value],
    queryFn: () => getStory(storyId.value),
    select(data) {
        return data.data
    },
})
</script>

<style scoped></style>
