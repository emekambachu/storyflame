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
                        title: 'About',
                        template: 'about',
                    },
                    {
                        title: 'Progress',
                        template: 'progress',
                    },
                    {
                        title: 'Elements',
                        template: 'elements',
                    },
                    {
                        title: 'Drafts',
                        template: 'drafts',
                    },
                ]"
                :no-animation="true"
                menu-btn-class="w-full text-base text-stone-500 bg-stone-100 px-4 py-3 rounded-lg border-none"
                menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-4 py-3 border-none"
                menu-container-class="w-full flex gap-2"
                menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3"
            >
                <story-header :story="story" />
                <template #about>
                    <story-about-tab :story="story" />
                </template>
                <template #drafts>
                    <story-drafts-tab :drafts="story.drafts" />
                </template>
            </tab-layout>

            <!--            <div class="relative flex items-center">-->
            <!--                <div-->
            <!--                    v-for="(tab, tabID) in tabs"-->
            <!--                    :key="tabID"-->
            <!--                    :class="-->
            <!--                        tabID == selectedTab-->
            <!--                            ? 'border-red-600 text-red-600'-->
            <!--                            : 'border-neutral-300 text-neutral-500'-->
            <!--                    "-->
            <!--                    class="relative z-10 mx-4 border-b-2 text-base font-normal"-->
            <!--                    @click="selectedTab = tabID"-->
            <!--                >-->
            <!--                    {{ tab }}-->
            <!--                </div>-->
            <!--                <div-->
            <!--                    class="z-5 absolute bottom-0 h-[2px] w-full bg-neutral-300"-->
            <!--                />-->
            <!--            </div>-->

            <!--            <story-about-tab-->
            <!--                v-if="selectedTab == 0"-->
            <!--                :story="story"-->
            <!--            />-->
            <!--            <story-drafts-tab-->
            <!--                v-if="selectedTab == 3"-->
            <!--                :drafts="story.drafts"-->
            <!--            />-->
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import StoryAboutTab from '@/components/StoryAboutTab.vue'
import StoryDraftsTab from '@/components/StoryDraftsTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import StoryHeader from '@/components/StoryHeader.vue'
import { useRoute } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { getStory } from '@/utils/endpoints'

const route = useRoute()
const storyId = computed(() => route.params.story)

const { data: story } = useQuery({
    queryKey: ['story', storyId.value],
    queryFn: () => getStory(storyId.value),
    select(data) {
        return data.data
    },
})

// const story = {
//     image: { path: 'https://picsum.photos/900' },
//     name: 'Game of Thrones',
//     genre: 'TV Show, MA-16',
//     percent: 80,
//     genres: ['Fantasy', 'Drama', 'Adventure', 'Political Intrigue'],
//     description:
//         'In a world where summers span decades and winters can last a lifetime, the noble houses of Westeros battle for control of the Seven Kingdoms, while an ancient enemy awakens in the North, threatening the realm with destruction.',
//     goals: [
//         'Explore a Unique Setting',
//         'Develop Complex Character Arcs',
//         'Craft a Cliffhanger Ending',
//     ],
//     market_comps: [
//         { image: { path: 'https://picsum.photos/300' } },
//         { image: null },
//         { image: { path: 'https://picsum.photos/600' } },
//     ],
//
// }

const tabs = ['About', 'Progress', 'Elements', 'Drafts']

const selectedTab = ref(0)
</script>

<style scoped></style>
