<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-4 pb-8">
            <!-- 
                    { title: 'Overview', template: 'overview' },
             -->
            <tab-layout
                :tabs="[
                    { title: 'Overview', template: 'overview' },
                    { title: 'Develop', template: 'develop' },
                    { title: 'Arcs', template: 'arcs' },
                    { title: 'References', template: 'references' },
                ]"
                header-height="300"
                class="!gap-0"
            >
                <header-animated>
                    <plot-header
                        :plot="data"
                        class="py-5"
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
                <tab-layout-view class="w-full bg-white pb-6">
                    <template #overview>
                        <plot-overview-tab :data="data" />
                    </template>
                    <template #develop>
                        <develop-tab :data="data" />
                    </template>
                    <template #arcs>
                        <plot-arcs-tab :data="data" />
                    </template>
                    <template #references>
                        <plot-references-tab :data="data" />
                    </template>
                </tab-layout-view>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import TabLayout from '@/components/TabLayout.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'

import DevelopTab from '@/components/DevelopTab.vue'

import PlotArcsTab from '@/components/plot/PlotArcsTab.vue'
import PlotOverviewTab from '@/components/plot/PlotOverviewTab.vue'
import PlotReferencesTab from '@/components/plot/PlotReferencesTab.vue'

import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import PlotHeader from '@/components/headers/PlotHeader.vue'

const data = {
    tags: ['Main plot'],
    readiness: 60,
    title: 'The Battle of Blackwater',
    description:
        'Has a deep-seated desire for love and acceptance, despite his cynical exterior',

    role: 'Protagonist',
    quote: 'I have a tender spot in my heart for cripples, bastards, and broken things',

    features: ['The Patriarch', 'Noble Top Enforcer'],

    progress_description:
        'Your characters and themes are looking great, but your story could use some world building development.',

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

    main_protagonist: {
        title: 'Tywin Lannister',
        description:
            'Exerts control over the realm through strategic alliances and military strength.',
    },
    goal: 'Tyrion`s primary goal is to defend King`s Landing and secure victory against Stannis Baratheon`s forces, thereby preserving the rule of House Lannister and maintaining his position of power and influence within the city.',
    exploration:
        'Throughout the battle, Tyrion explores his leadership abilities and strategic acumen, demonstrating his resourcefulness and resilience in the face of adversity. He navigates the complexities of wartime politics and military tactics, forging alliances and making difficult decisions to ensure the city`s survival.',

    story_points: [
        {
            title: 'Setup',
            description:
                'The episode establishes the status quo of the Stark family in Winterfell, introducing Ned Stark as an honorable and dutiful lord, his wife Catelyn, and their children. It also sets up the political landscape of Westeros and the looming threat of the White Walkers beyond the Wall.',
        },
        {
            title: 'Inciting Incident',
            description:
                "King Robert Baratheon arrives at Winterfell and asks Ned to serve as the new Hand of the King, following the suspicious death of the previous Hand, Jon Arryn. This event disrupts Ned's life and sets the main plot in motion, forcing him to leave his family and navigate the dangerous political waters of King's Landing.",
        },
        {
            title: 'Trials and Complications',
            description:
                "In the pilot episode, the trials and complications are primarily established through exposition and foreshadowing. Ned's reluctance to accept the position, the tensions between the Starks and Lannisters, and the introduction of the exiled Targaryens across the Narrow Sea all hint at the challenges and obstacles to come.",
        },
        {
            title: 'The Midpoint Twist',
            description:
                "As this is a pilot episode, there is no clear midpoint twist. However, the episode does end with a shocking moment - Bran Stark's fall from the tower after witnessing Jaime and Cersei Lannister's incestuous relationship. This event sets up a major plot point and source of conflict for future episodes.",
        },
        {
            title: 'The Crisis Point',
            description:
                "The pilot episode does not reach a clear crisis point for the protagonist, as it primarily focuses on setting up the characters and their world. However, Bran's fall and the implied consequences for the Stark family could be seen as a mini-crisis point that will lead to further complications.",
        },
        {
            title: 'The Climax',
            description:
                "The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran's fall, which leaves the audience with a sense of suspense and uncertainty.",
        },
        {
            title: 'The Resolution',
            description:
                "The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran's fall, which leaves the audience with a sense of suspense and uncertainty.",
        },
    ],
}
</script>

<style scoped></style>
