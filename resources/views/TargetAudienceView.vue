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
                        title: 'Main',
                        template: 'main',
                    },
                ]"
                :no-animation="true"
                collapse-header-height="120"
                header-height="296"
            >
                <header-animated>
                    <target-audience-header :data="data" />
                </header-animated>

                <tab-layout-view>
                    <template #main>
                        <div class="flex w-full flex-col gap-2 bg-slate-100">
                            <title-section
                                v-if="data.fandoms?.length"
                                class="bg-white px-3 py-3"
                            >
                                <template #title>
                                    <h4 class="text-lg font-bold text-black">
                                        Favorite Fandoms
                                    </h4>
                                </template>
                                <div
                                    class="flex w-full max-w-full gap-3 overflow-scroll"
                                >
                                    <image-component
                                        v-for="(item, itemID) in data.fandoms"
                                        :key="itemID"
                                        :src="item?.image?.path"
                                        class="h-[155px] w-[110px] shrink-0 rounded-lg object-cover"
                                    />
                                </div>
                            </title-section>

                            <title-section
                                v-if="data.aspects_of_apeal?.length"
                                class="bg-white px-3 py-3"
                            >
                                <template #title>
                                    <h4 class="text-lg font-bold text-black">
                                        Key Aspects of Appeal
                                    </h4>
                                </template>

                                <subtitle-section
                                    v-for="(
                                        item, itemID
                                    ) in data.aspects_of_apeal"
                                    :key="itemID"
                                    :title="item?.title"
                                    title-class="text-stone-900 font-semibold"
                                    :description="item?.description"
                                />
                            </title-section>

                            <title-section
                                v-if="data.fandoms?.length"
                                class="bg-white px-3 py-3"
                            >
                                <template #title>
                                    <h4 class="text-lg font-bold text-black">
                                        This Not Thats
                                    </h4>
                                </template>

                                <target-audience-card
                                    v-for="(
                                        item, itemID
                                    ) in data.target_audience"
                                    :key="itemID"
                                    :card="item"
                                    title-class="text-slate-600 text-sm font-bold"
                                    class="!bg-white !p-0"
                                />

                                <subtitle-section
                                    class="w-full"
                                    title="Interests:"
                                    title-class="text-sm text-slate-600 font-bold"
                                >
                                    <div
                                        class="flex w-full items-start justify-between gap-2"
                                    >
                                        <div
                                            class="flex w-full max-w-[170px] flex-col items-start gap-3 overflow-auto"
                                        >
                                            <interest-card
                                                v-for="(
                                                    interest, interestID
                                                ) in data.interest"
                                                :key="interestID"
                                                :card="interest"
                                            />
                                        </div>
                                        <div
                                            class="flex w-full max-w-[170px] flex-col items-start gap-3 overflow-auto"
                                        >
                                            <interest-card
                                                v-for="(
                                                    interest, interestID
                                                ) in data.not_interest"
                                                :key="interestID"
                                                :card="interest"
                                            />
                                        </div>
                                    </div>
                                </subtitle-section>
                            </title-section>

                            <title-section
                                v-if="data.additional_insights?.length"
                                class="bg-white px-3 py-3"
                            >
                                <template #title>
                                    <h4 class="text-lg font-bold text-black">
                                        Additional Insights
                                    </h4>
                                </template>
                                <subtitle-section
                                    v-for="(
                                        item, itemID
                                    ) in data.additional_insights"
                                    :key="itemID"
                                    :title="item?.title"
                                    title-class="text-stone-900 font-semibold"
                                    :description="item?.description"
                                />
                            </title-section>
                        </div>
                    </template>
                </tab-layout-view>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
// import { getStory } from '@/utils/endpoints'
// import { useQuery } from '@tanstack/vue-query'

import ImageComponent from '@/components/ImageComponent.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TargetAudienceHeader from '@/components/headers/TargetAudienceHeader.vue'

import TabLayout from '@/components/TabLayout.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

import TitleSection from '@/components/TitleSection.vue'
import SubtitleSection from '@/components/cards/SubtitleSection.vue'

import InterestCard from '@/components/cards/InterestCard.vue'
import TargetAudienceCard from '@/components/cards/TargetAudienceCard.vue'

const route = useRoute()
// const outlineId = computed(() => route.params.outline)

// const { data: outline } = useQuery({
//     queryKey: ['outline', outlineId.value],
//     queryFn: () => getOutline(outlineId.value),
//     select(data) {
//         return data.data
//     },
// })

const data = {
    title: 'Some title here',
    tags: ['Target Audience'],
    genres: ['Game of Thrones', 'Ep 101: The Call North'],
    progress: 80,
    image: {
        path: 'https://picsum.photos/900',
    },
    description:
        'Craves complex world-building, political intrigue, and morally ambiguous characters, seeks stories with high stakes and unpredictable plot twists.',

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
    fandoms: [
        {
            image: {
                path: 'https://picsum.photos/600',
            },
        },
        {
            image: {
                path: 'https://picsum.photos/600',
            },
        },
        {
            image: {
                path: 'https://picsum.photos/600',
            },
        },
    ],
    aspects_of_apeal: [
        {
            title: 'Complex Characters',
            description:
                'Fans are drawn to the morally gray, multidimensional characters that populate the world of Game of Thrones',
        },
        {
            title: 'Intricate Storylines',
            description:
                "The series' interweaving plotlines, political machinations, and unexpected twists keep audiences engaged and guessing",
        },
        {
            title: 'Immersive World-Building',
            description:
                'The rich, detailed world of Westeros and Essos, with its deep history and lore, captivates fans',
        },
        {
            title: 'High Stakes',
            description:
                'The constant threat of danger, the looming presence of the White Walkers, and the high-stakes game of power create a thrilling viewing experience',
        },
    ],

    target_audience: [
        {
            title: 'General',
            description: null,
            prefer: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
            dislike:
                'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
        },
    ],

    interest: [
        {
            prefer: true,
            description: 'Fantasy worlds',
        },
        {
            prefer: true,
            description: 'Simplistic plots',
        },
    ],
    not_interest: [
        {
            prefer: false,
            description: 'Fantasy worlds',
        },
        {
            prefer: false,
            description: 'Simplistic plots',
        },
    ],

    additional_insights: [
        {
            title: 'Book Readers vs. Show Watchers',
            description:
                'There is a subset of the audience who have read the A Song of Ice and Fire novels, which can lead to differing expectations and reactions to the series.',
        },
        {
            title: 'Controversy and Criticism',
            description:
                'Some fans have been vocal about their disappointment with certain plot developments, particularly in the final season, which is important to keep in mind when engaging with the fanbase.',
        },
        {
            title: 'Spinoff Potential',
            description:
                'With the conclusion of the main series, many fans are eager for more content set in the Game of Thrones universe, such as the upcoming House of the Dragon prequel series.',
        },
    ],
}
</script>

<style scoped></style>
