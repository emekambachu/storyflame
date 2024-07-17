<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-6 pb-6">
            <tab-layout
                :tabs="[
                    {
                        title: 'Overview',
                        template: 'overview',
                    },
                    {
                        title: 'Comparison',
                        template: 'comparison',
                    },
                ]"
                :no-animation="true"
                collapse-header-height="120"
                header-height="440"
            >
                <header-animated>
                    <market-comp-header :market_comp="market_comp" />
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
                        <market-comp-overview-tab :data="market_comp" />
                    </template>
                    <template #comparison>
                        <market-comp-comparison-tab :data="market_comp" />
                    </template>
                </tab-layout-view>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { useRoute } from 'vue-router'
// import { getMarketComp} from '@/utils/endpoints'
// import { useQuery } from '@tanstack/vue-query'

import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import MarketCompHeader from '@/components/headers/MarketCompHeader.vue'

import TabLayout from '@/components/TabLayout.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

import MarketCompOverviewTab from '@/components/market_comp/MarketCompOverviewTab.vue'
import MarketCompComparisonTab from '@/components/market_comp/MarketCompComparisonTab.vue'

const route = useRoute()
// const market_comp_id = computed(() => route.params.market_comp)

// const { data: market_comp } = useQuery({
//     queryKey: ['market_comp', market_comp_id.value],
//     queryFn: () => getmarket_comp(market_comp_id.value),
//     select(data) {
//         return data.data
//     },
// })

const market_comp = {
    title: 'Some title here',
    description:
        'Explores complex character dynamics, immersive world-building, and high-stakes political intrigue within a richly established fantasy universe.',
    tags: ['Market comp'],
    genres: ['Fantasy ', 'Drama', 'Adventure', 'Political Intrigue'],

    progress: 80,
    image: {
        path: 'https://picsum.photos/900',
    },

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
    favorite_aspects: [
        {
            title: 'When Dragons Rule',
            description:
                '“House of the Dragon” is celebrated for its focus on Targaryen lore, complex character dynamics, and high-stakes political intrigue within a richly established fantasy universe.',
        },
    ],
    top_similarities: [
        {
            list: [
                'Both feature intricate political plots and familial conflicts.',
                'Both pilots set up central conflicts and introduce a wide array of key characters whose ambitions drive the story forward.',
            ],
        },
    ],
    top_differences: [
        {
            list: [
                '“House of the Dragon” focuses more on internal family dynamics and historical context, while “Game of Thrones” has a broader scope involving multiple families and regions.',
                'The setting and tone in “House of the Dragon” are steeped in Targaryen history and lore, whereas “Game of Thrones” encompasses a wider geographical and cultural landscape.',
            ],
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

    plot: [
        {
            title: 'Description',
            description:
                'Represents the archetypal "noble hero" whose adherence to honor and justice conflicts with the corrupt world of Westeros. His arc in the pilot sets up the central thematic conflict between morality and power.',
        },
        {
            title: 'Plot Arc',
            description:
                'The pilot establishes the brewing tension within House Targaryen, highlighting key characters’ ambitions and setting the stage for the civil war. Significant events, such as the introduction of key players and their motivations, lay the groundwork for the impending conflict.',
        },
        {
            title: 'Plot Compared',
            description:
                '“House of the Dragon” offers a rich exploration of internal family dynamics and power struggles within a single house, whereas “Game of Thrones” encompasses a broader scope involving multiple families and regions. Both pilots establish intricate political plots and familial conflicts, setting up complex narratives driven by character ambitions.',
            lists: [
                {
                    listTitle: 'Similarities',
                    list: [
                        'Both feature intricate political plots and familial conflicts.',
                    ],
                },
                {
                    listTitle: 'Differences',
                    list: [
                        '“House of the Dragon” focuses more on internal family dynamics and historical context, while “Game of Thrones” has a broader scope involving multiple families and regions.',
                    ],
                },
            ],
        },
    ],

    characters: [
        {
            title: 'Key Characters',
            list: [
                'Rhaenyra Targaryen: A strong-willed princess determined to secure her birthright as the heir to the Iron Throne.',
                'Daemon Targaryen: The ambitious and volatile brother of King Viserys, whose quest for power drives much of the conflict.',
                'Alicent Hightower: A key political player whose alliances and actions significantly influence the power dynamics within the realm.',
            ],
        },
        {
            title: 'Character Arcs',
            description:
                'Characters in “House of the Dragon” are introduced with clear motivations and complex relationships, setting up their future arcs of betrayal, ambition, and loyalty.',
        },
        {
            title: 'Characters Compared',
            description:
                'The character dynamics in “House of the Dragon” offer a template for creating layered and evolving relationships.',
            lists: [
                {
                    listTitle: 'Similarities',
                    list: [
                        'Complex character arcs with significant growth and change.',
                    ],
                },
                {
                    listTitle: 'Differences',
                    list: [
                        'Focuses more on the Targaryen family and their internal struggles.',
                    ],
                },
            ],
        },
    ],

    themes: [
        {
            title: 'Central Themes',
            description:
                '“House of the Dragon” explores themes of power, succession, and the burdens of legacy. The series delves into the moral compromises and personal sacrifices characters make to achieve their goals.',
        },
        {
            title: 'Symbols & Motifs',
            list: [
                'Dragons: Symbolize Targaryen power and the destructive potential of their legacy.',
                'The Iron Throne: Continues to represent ultimate power and the lengths individuals will go to obtain it.',
                'Fire and Blood: Reflects the Targaryen family motto and the violence and passion inherent in their rule.',
            ],
        },

        {
            title: 'Themes Compared',
            description:
                'The themes in “House of the Dragon” can inspire stories that examine the complexities of power and legacy within a family.',
            lists: [
                {
                    listTitle: 'Similarities',
                    list: ['Focus on power struggles and moral dilemmas.'],
                },
                {
                    listTitle: 'Differences',
                    list: [
                        'More emphasis on the internal conflicts of a single powerful family.',
                    ],
                },
            ],
        },
    ],

    world: [
        {
            title: 'World Details',
            description:
                '“House of the Dragon” expands on the world of Westeros, with a focus on King’s Landing and Dragonstone. The series enriches the lore of Westeros by detailing Targaryen history and the geographical and political landscape during their reign.',
        },
        {
            title: 'Tone and Style',
            description:
                'The tone of “House of the Dragon” is similarly dark and mature, with a focus on political intrigue and intense personal drama. The visual style maintains a medieval aesthetic with grandiose set pieces and costumes.',
        },

        {
            title: 'World Compared',
            description:
                'The world-building in “House of the Dragon” offers a detailed and immersive setting that can inform the creation of rich, lived-in worlds.',
            lists: [
                {
                    listTitle: 'Similarities',
                    list: [
                        'Detailed world-building with a mix of realism and fantasy.',
                    ],
                },
                {
                    listTitle: 'Differences',
                    list: [
                        'Focuses more on the historical and cultural context of the Targaryen era.',
                    ],
                },
            ],
        },
    ],
}
</script>

<style scoped></style>
