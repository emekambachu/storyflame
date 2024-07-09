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
            >
                <!--                <story-header :story="story" />-->
                <header-animated
                    collapse-header-height="120"
                    header-height="340"
                >
                    <!--                    <default-element-header height="478px" />-->
                    <story-header
                        v-if="testStory"
                        :story="testStory"
                    />
                    <template #sticky>
                        <tab-layout-tabs
                            menu-btn-class="w-full text-base text-stone-500 bg-stone-100 px-3 py-2 rounded-lg border-none"
                            menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-3 py-2 border-none"
                            menu-container-class="w-full flex gap-2"
                            menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3 bg-white"
                        />
                    </template>
                </header-animated>
                <template #develop>
                    <develop-tab
                        :data="testStory"
                        startSmthNew
                    />
                </template>
                <template #progress>
                    <story-progress-tab :story="testStory" />
                </template>
                <template #arcs>
                    <story-story-tab :story="testStory" />
                </template>
                <template #elements>
                    <story-elements-tab :story="testStory" />
                </template>
                <template #references>
                    <story-marketing-tab :story="testStory" />
                </template>
            </tab-layout-view>
        </tab-layout>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import StoryStoryTab from '@/components/story/StoryStoryTab.vue'
import DevelopTab from '@/components/DevelopTab.vue'
import StoryProgressTab from '@/components/story/StoryProgressTab.vue'
import StoryElementsTab from '@/components/story/StoryElementsTab.vue'
import StoryMarketingTab from '@/components/story/StoryMarketingTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import { useRoute } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { getStory } from '@/utils/endpoints'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import StoryHeader from '@/components/headers/StoryHeader.vue'

const route = useRoute()
const storyId = computed(() => route.params.story)

const { data: story} = useQuery({
    queryKey: ['story', storyId.value],
    queryFn: () => getStory(storyId.value),
    select(data) {
        return data.data
    },
})

const testStory = {
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
    },
    characters_list: {
        title: 'Characters',
        progress: 2,
        achievements: [
            { image: { path: 'https://picsum.photos/900' } },
            { image: { path: 'https://picsum.photos/900' } },
        ],
        amount_max_achievements: 12,

        effectiveness:
            'The plot sets up conflicts and power dynamics that have the potential to test the characters` morality, but the stakes and central tensions need to be heightened to fully realize the writer`s vision of exploring the cost of power in a gritty world.',
        audience:
            'The morally grey characters and their high-stakes conflicts should appeal to the target audience of mature fantasy drama fans. However, the characters need to be quickly established as compelling and relatable to fully engage viewers. Strengthening the sense of character perspective and ensuring each one has a clear goal will help audiences invest in their journeys.',
        market_comparisons:
            'Prospera`s characters have the complex moral shading and mix of noble and selfish motivations that define prestige fantasy drama protagonists. However, their distinct personalities and voices need to be sharpened to match the memorable characterization of market comp ensembles. The characters need to command the screen and leave the audience eager to follow them.',
        potential_explorations: [
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
        something_new: [
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
    },
    settings: {
        title: 'Settings',
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
    },
    themes: [
        {
            progress: 100,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 100,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
        {
            progress: 100,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
    ],
    story_info: [
        {
            title: 'Setup',
            description:
                'The episode establishes the status quo of the Stark family in Winterfell, introducing Ned Stark as an honorable and dutiful lord, his wife Catelyn, and their children. It also sets up the political landscape of Westeros and the looming threat of the White Walkers beyond the Wall.',
        },
        {
            title: 'Inciting Incident',
            description:
                'King Robert Baratheon arrives at Winterfell and asks Ned to serve as the new Hand of the King, following the suspicious death of the previous Hand, Jon Arryn. This event disrupts Ned`s life and sets the main plot in motion, forcing him to leave his family and navigate the dangerous political waters of King`s Landing.',
        },
        {
            title: 'Trials and Complications',
            description:
                'In the pilot episode, the trials and complications are primarily established through exposition and foreshadowing. Ned`s reluctance to accept the position, the tensions between the Starks and Lannisters, and the introduction of the exiled Targaryens across the Narrow Sea all hint at the challenges and obstacles to come.',
        },
        {
            title: 'The Midpoint Twist',
            description:
                'As this is a pilot episode, there is no clear midpoint twist. However, the episode does end with a shocking moment - Bran Stark`s fall from the tower after witnessing Jaime and Cersei Lannister`s incestuous relationship. This event sets up a major plot point and source of conflict for future episodes.',
        },
        {
            title: 'The Crisis Point',
            description:
                'The pilot episode does not reach a clear crisis point for the protagonist, as it primarily focuses on setting up the characters and their world. However, Bran`s fall and the implied consequences for the Stark family could be seen as a mini-crisis point that will lead to further complications.',
        },
        {
            title: 'The Climax',
            description:
                'The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran`s fall, which leaves the audience with a sense of suspense and uncertainty.',
        },
        {
            title: 'The Resolution',
            description:
                'The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran`s fall, which leaves the audience with a sense of suspense and uncertainty.',
        },
        {
            title: 'The Hook',
            description:
                'The pilot episode of Game of Thrones presents several hooks to engage the audience and encourage them to continue watching the series:',
            list: [
                'The complex political intrigue and power struggles hinted at throughout the episode',
                'The looming supernatural threat of the White Walkers beyond the Wall',
                'The shocking final scene with Bran`s fall and the revelation of Jaime and Cersei`s secret relationship',
                'The introduction of compelling characters with rich backstories and motivations, such as Ned Stark, Daenerys Targaryen, and Tyrion Lannister',
            ],
        },
    ],
}

// const { data: story } = useQuery({
//     queryKey: ['story', storyId.value],
//     queryFn: () => getStory(storyId.value),
//     select(data) {
//         return data.data
//     },
// })
</script>

<style scoped></style>
