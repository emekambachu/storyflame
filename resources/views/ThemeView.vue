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
                    { title: 'Develop', template: 'develop' },
                    { title: 'Progression', template: 'progression' },
                    { title: 'References', template: 'references' },
                ]"
                class="!gap-0"
                collapse-header-height="120"
                header-height="283"
            >
                <header-animated>
                    <theme-header
                        :theme="theme"
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

                <tab-layout-view>
                    <template #overview>
                        <theme-overview-tab :data="theme" />
                    </template>
                    <template #develop>
                        <develop-tab :data="theme" />
                    </template>
                    <template #progression>
                        <theme-progression-tab :data="theme" />
                    </template>
                    <template #references>
                        <theme-references-tab :data="theme" />
                    </template>
                </tab-layout-view>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import TabLayout from '@/components/TabLayout.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'

import ThemeOverviewTab from '@/components/theme/ThemeOverviewTab.vue'
import DevelopTab from '@/components/DevelopTab.vue'
import ThemeProgressionTab from '@/components/theme/ThemeProgressionTab.vue'
import ThemeReferencesTab from '@/components/theme/ThemeReferencesTab.vue'

import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import ThemeHeader from '@/components/headers/ThemeHeader.vue'

const theme = {
    name: 'Power',
    readiness: 60,
    image: {
        path: 'https://placekeanu.com/600/400',
    },
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

    progression: {
        character_embodiments: [
            {
                title: 'Ned Stark',
                description:
                    'Represents the archetypal "noble hero" whose adherence to honor and justice conflicts with the corrupt world of Westeros. His arc in the pilot sets up the central thematic conflict between morality and power.',
            },
            {
                title: 'Cersei Lannister',
                description:
                    'Embodies the theme of moral compromise for the sake of power. Her actions, such as her incestuous relationship with Jaime and her plotting to secure her family`s power, demonstrate the lengths some characters will go to maintain control.',
            },
        ],
        key_moments: [
            {
                headerTitle: 'Favorite Moment',
                title: 'Opening Scene',
                description:
                    'The pilot`s opening scene, featuring the White Walkers and the subsequent execution of a deserter, establishes the harsh and morally ambiguous world that reflects the theme.',
            },
            {
                headerTitle: 'Favorite Moment',
                title: 'Bran`s Fall',
                description:
                    'The pilot`s opening scene, featuring the White Walkers and the subsequent execution of a deserter, establishes the harsh and morally ambiguous world that reflects the theme.',
            },
        ],

        thematic_progression_conflict: [
            {
                title: 'Thematic Establishment',
                description:
                    "The pilot episode introduces the central theme through various characters and their actions, such as Jaime and Cersei's moral transgression, and Viserys Targaryen's cruel treatment of his sister Daenerys in his quest for power.",
            },
            {
                title: 'Rising Conflict',
                description:
                    'As the episode progresses, the theme is further developed through the tensions between the Starks and the Lannisters, as well as the introduction of the exiled Targaryens plotting to reclaim their lost power.',
            },
            {
                title: 'Thematic Climax',
                description:
                    "While the pilot serves as an introduction to the series, the thematic climax of this episode occurs when Bran discovers Jaime and Cersei's secret, leading to his fall from the tower. This moment sets in motion the central conflicts that will drive the narrative forward.",
            },
        ],

        symbols_motifs: [
            {
                title: 'The Iron Throne',
                description:
                    'The Iron Throne serves as a visual symbol of the ruthless pursuit of power. Its design, made from the swords of vanquished enemies, represents the violence and conquest often associated with obtaining and maintaining power in Westeros.',
            },
            {
                title: '“The things I do for love”',
                description:
                    'Jaime`s line before pushing Bran from the tower window becomes a recurring phrase that highlights the moral compromises characters make for the sake of their personal attachments and desires, often tied to their pursuit of power.',
            },
        ],
    },

    references: {
        historical: [
            {
                image: {
                    path: 'https://picsum.photos/900',
                },
                title: "The rise and fall of Richard Nixon's presidency",
                description:
                    "The presidency of Richard Nixon serves as a striking example of the corrupting influence of power. Nixon's involvement in the Watergate scandal, which included attempts to cover up a break-in at the Democratic National Committee headquarters, ultimately led to his resignation. Nixon's story showcases how the desire to maintain power can lead to unethical behavior and the erosion of public trust.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            "Like the characters in Game of Thrones, Nixon's downfall was precipitated by a web of secrets, lies, and moral compromises.",
                            'The scandal exposed the lengths to which those in power will go to protect their position.',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "The consequences of Nixon's actions had far-reaching effects on American politics and society.",
                        ],
                    },
                ],
            },
            {
                image: {
                    path: 'https://picsum.photos/900',
                },
                title: 'The reign of King Henry VIII of England',
                description:
                    "King Henry VIII's reign is marked by his increasingly tyrannical behavior and the moral compromises he made to maintain power. Henry's decision to break with the Catholic Church and establish himself as the head of the Church of England, as well as his numerous marriages and the execution of several of his wives, exemplify the corrupting influence of absolute power.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            "Like the characters in Game of Thrones, Henry VIII's story involves political maneuvering, betrayal, and the blurring of moral lines in the pursuit of power.",
                            'Both stories feature rulers who become increasingly ruthless as their power grows.',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Henry VIII's reign is a historical example of the corrupting influence of power in a monarchical system.",
                            "The real-life consequences of Henry's actions had a profound impact on English history and religion.",
                        ],
                    },
                ],
            },
        ],
        narrative: [
            {
                image: {
                    path: 'https://picsum.photos/900',
                },
                title: 'Walter White in Breaking Bad',
                description:
                    "In the television series Breaking Bad, the character of Walter White embodies the corrupting influence of power. As a high school chemistry teacher who turns to producing methamphetamine to secure his family's financial future, Walter's increasing involvement in the drug trade leads him to commit increasingly heinous acts and compromise his moral integrity.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            "Walter White's story is one of a seemingly ordinary person who is corrupted by the power they acquire.",
                            'Both stories explore the gradual erosion of moral boundaries and the consequences of unchecked ambition.',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Breaking Bad focuses on a single character's transformation, whereas Game of Thrones explores the theme through multiple characters and storylines.",
                        ],
                    },
                ],
            },
            {
                image: {
                    path: 'https://picsum.photos/900',
                },
                title: 'The character of Anakin Skywalker/Darth Vader in the Star Wars prequel trilogy',
                description:
                    "In the Star Wars prequel trilogy, the character of Anakin Skywalker, who eventually becomes Darth Vader, exemplifies the corrupting influence of power. Anakin's desire for power and his fear of losing those he loves lead him to embrace the dark side of the Force, ultimately corrupting him and transforming him into the villainous Darth Vader.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            "Like the characters in Game of Thrones, Anakin Skywalker's story involves the gradual corruption of a character who starts with noble intentions.",
                            "Both stories explore how the pursuit of power can lead to the betrayal of one's values and the destruction of relationships.",
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Anakin's story is ultimately one of redemption, whereas Game of Thrones often portrays the consequences of corruption as more permanent and irreversible.",
                        ],
                    },
                ],
            },
        ],
    },
}
</script>

<style scoped></style>
