<template>
    <div class="flex w-full flex-col items-center">
        <tab-layout
            :tabs="[
                { title: 'Progress', template: 'stories' },
                { title: 'Continue', template: 'continue' },
                { title: 'Clarify', template: 'clarify' },
                { title: 'New', template: 'new' },
                { title: 'Transcripts', template: 'transcripts' },
            ]"
            class="!gap-0"
            no-animation
            scroll-to-page-section
            tab-content-class="pb-6 w-full bg-white"
            tabs-content-class="w-full flex flex-col gap-2 bg-slate-100"
        >
            <div class="flex w-full flex-col gap-3 bg-neutral-950 px-4 py-8">
                <div class="flex w-full items-center justify-between">
                    <logo-icon />

                    <router-link :to="{
                        name: 'profile',

                    }" class="h-8 w-8 shrink-0 rounded-full bg-white"></router-link>
                </div>

                <p class="text-sm font-medium uppercase text-slate-400">
                    {{ data.smth }}
                </p>

                <div class="flex items-center justify-between gap-3">
                    <achievement-summary-card
                        v-for="(card, cardID) in data.achievements_summary"
                        :key="cardID"
                        :item="card"
                        card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"
                        value-class="text-sm text-pure-white font-bold"
                    />
                </div>
            </div>
            <template #stories>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <h4 class="text-lg font-bold text-black">
                            Recent Stories
                        </h4>
                    </template>
                    <div class="flex w-full max-w-full gap-4 overflow-scroll">
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
                            class="!p-0"
                            title="Continue where you left off"
                            title-class="text-lg text-black font-bold"
                        />
                    </template>
                    <achievement-completed-card
                        v-for="(
                            achievement, achievementID
                        ) in data.achievements_completed"
                        :key="achievementID"
                        :card="achievement"
                    />
                    <achievement-in-progress-card
                        v-for="(
                            achievement, achievementID
                        ) in data.achievements_in_progress"
                        :key="achievementID"
                        :card="achievement"
                    />
                </title-section>
            </template>
            <template #clarify>
                <title-section
                    class="!gap-6 px-4 py-6"
                    style="
                        background: linear-gradient(
                                180deg,
                                rgba(0, 0, 0, 0) 0%,
                                #000 100%
                            ),
                            linear-gradient(
                                0deg,
                                rgba(0, 0, 0, 0.3) 0%,
                                rgba(0, 0, 0, 0.3) 100%
                            ),
                            linear-gradient(0deg, #404040 0%, #404040 100%),
                            #fff;
                    "
                >
                    <template #title>
                        <title-with-link
                            class="!p-0"
                            title="Clarify discrepancies"
                            title-class="text-lg text-white font-bold"
                        />
                    </template>

                    <discrepancies-card
                        v-for="(card, cardID) in data.discrepancies"
                        :key="cardID"
                        :card="card"
                    />
                </title-section>
            </template>

            <template #new>
                <title-section class="!gap-6 px-4 py-6">
                    <template #title>
                        <title-with-link
                            class="!p-0"
                            title="Starting Something New"
                            title-class="text-lg text-black font-bold"
                        />
                        <div
                            class="flex w-full max-w-full items-center gap-3 overflow-scroll"
                        >
                            <div
                                v-for="(item, itemID) in createNew"
                                :key="itemID"
                                class="flex w-full flex-col items-center gap-1 text-center"
                            >
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-stone-100 font-normal"
                                >
                                    <component
                                        :is="item?.icon"
                                        class="text-stone-400"
                                    />
                                </div>
                                <span
                                    class="text-xs font-semibold text-stone-500"
                                >
                                    {{ item.title }}
                                </span>
                            </div>
                        </div>
                        <hr class="mx-auto my-1 w-48 text-stone-300" />
                        <achievement-in-progress-card
                            v-for="(
                                achievement, achievementID
                            ) in data.achievements_in_progress"
                            :key="achievementID"
                            :card="achievement"
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
        <!--        <div-->
        <!--            v-if="showDiscuss"-->
        <!--            class="fixed bottom-0 left-0 right-0 px-4 pb-3"-->
        <!--        >-->
        <!--            <discuss-component @close="showDiscuss = false" />-->
        <!--        </div>-->
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
// import { useAuthStore } from '@/stores/auth'
import SeriesIcon from '@/components/icons/SeriesIcon.vue'
import BookWithPlus from '@/components/icons/BookWithPlus.vue'
import UserWithPlus from '@/components/icons/UserWithPlus.vue'
import SequencesWithPlus from '@/components/icons/SequencesWithPlus.vue'

import TabLayout from '@/components/TabLayout.vue'
import AchievementInProgressCard from '@/components/cards/AchievementInProgressCard.vue'
import HomeStatisticComponent from '@/components/HomeStatisticComponent.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import TitleSection from '@/components/TitleSection.vue'
import DiscrepanciesCard from '@/components/cards/DiscrepanciesCard.vue'
import AchievementCompletedCard from '@/components/cards/AchievementCompletedCard.vue'
import RecentStoryCard from '@/components/cards/RecentStoryCard.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'
import LogoIcon from '@/components/icons/LogoIcon.vue'
import NewStoryCard from '@/components/cards/NewStoryCard.vue'

// const appName = import.meta.env.VITE_APP_NAME

// const { logout, isLoggedIn } = useAuthStore()

const showDiscuss = ref(true)

const createNew = [
    { icon: SeriesIcon, title: 'Series' },
    { icon: BookWithPlus, title: 'Story' },
    { icon: UserWithPlus, title: 'Character' },
    { icon: SequencesWithPlus, title: 'Sequence' },
]
const data = {
    smth: '[something celebratory]',
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

    achievements_in_progress: [
        {
            icon: {
                path: 'https://picsum.photos/90',
            },
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
}
</script>
