<template>
    <div class="flex w-full flex-col items-center">
        <tab-layout
            :tabs="tabs"
            tabs-content-class="w-full flex flex-col gap-2 bg-slate-100"
        >
            <header-animated
                collapse-header-height="200"
                header-height="200"
            >
                <div
                    class="flex w-full flex-col gap-3 bg-neutral-950 px-4 py-8"
                >
                    <div class="flex w-full items-center justify-between">
                        <logo-icon />

                        <router-link
                            :to="{
                                name: 'profile',
                            }"
                            class="h-8 w-8 shrink-0 rounded-full bg-white"
                        ></router-link>
                    </div>

                    <p class="text-sm font-medium uppercase text-slate-400">
                        {{ data.smth }}
                    </p>

                    <div class="flex items-center justify-between gap-3">
<!--                        <achievement-summary-card-->
<!--                            v-for="(card, cardID) in data.achievements_summary"-->
<!--                            :key="cardID"-->
<!--                            :item="card"-->
<!--                            card-class="flex flex-col gap-2 p-2 rounded-lg bg-neutral-800 w-full max-w-28"-->
<!--                            value-class="text-sm text-pure-white font-bold"-->
<!--                        />-->
                    </div>
                </div>
                <template #sticky>
                    <tab-layout-tabs />
                </template>
            </header-animated>
            <tab-layout-view
                class="w-full bg-white pb-6"
                one-line
            >
                <template #profile>
                    <profile-tab :user="user" />
                </template>
                <template #stories>
                    <title-section class="!gap-6 py-6">
                        <template #title>
                            <title-with-link
                                :to="{
                                    name: 'stories',
                                }"
                            >
                                <h4 class="text-lg font-bold text-black">
                                    Recent Stories
                                </h4>
                            </title-with-link>
                        </template>
                        <horizontal-list :items="data.stories">
                            <template #item="{ item }">
                                <recent-story-card :story="item" />
                            </template>
                            <template #new>
                                <new-story-card class="h-24 shrink-0" />
                            </template>
                        </horizontal-list>
                    </title-section>
                </template>
                <template #achievements>
                    <title-section class="py-6">
                        <template #title>
                            <title-with-link>
                                <h4 class="text-lg font-bold text-black">
                                    Achievements
                                </h4>
                            </title-with-link>
                        </template>
                        <div class="px-4 gap-6 flex flex-col">
<!--                            <achievement-completed-card-->
<!--                                v-for="(achievement, achievementID) in user.achievements"-->
<!--                                :key="achievementID"-->
<!--                                :card="achievement"-->
<!--                            />-->
                        </div>
                    </title-section>
                </template>
                <template #membership>
                    <user-profile-subscription-tab :user="user" />
                </template>
            </tab-layout-view>
        </tab-layout>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import ProfileTab from '@/components/profile/ProfileTab.vue'
import ProfileStoriesTab from '@/components/profile/ProfileStoriesTab.vue'
import ProfileAchievementsTab from '@/components/profile/ProfileAchievementsTab.vue'
import TabLayout from '@/components/TabLayout.vue'
import UserProfileHeader from '@/components/headers/UserProfileHeader.vue'
import { useUser } from '@/composables/query/user'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import UserProfileSubscriptionTab from "@/components/userProfile/UserProfileSubscriptionTab.vue"
import TitleWithLink from '@/components/TitleWithLink.vue'
import TitleSection from '@/components/TitleSection.vue'
import HorizontalList from '@/components/ui/HorizontalList.vue'
import RecentStoryCard from '@/components/cards/RecentStoryCard.vue'
import NewStoryCard from '@/components/cards/NewStoryCard.vue'
import AchievementCompletedCard from '@/components/cards/AchievementCompletedCard.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'
import LogoIcon from '@/components/icons/LogoIcon.vue'
import { useAuthStore } from '@/stores/auth'

const { data: user } = useUser()
const authStore = useAuthStore()

const tabs = [
    { title: 'My profile', template: 'profile' },
    { title: 'My stories', template: 'stories' },
    { title: 'My achievements', template: 'achievements' },
    { title: 'Membership', template: 'membership' },
]

const data = {
    smth: '[something celebratory]',
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
    achievements_summary: [
        { title: 'Earned', value: 10 },
        { title: 'Progress', value: 0 },
        { title: 'Up to next', value: 0 },
    ],
}
</script>

<style scoped></style>
