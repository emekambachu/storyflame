<template>
    <div
        class="w-full min-h-screen flex flex-col items-center pt-10 pb-24 px-4"
    >
        <template v-if="loading || !user">
            <loading-tab class="w-full min-h-screen" />
        </template>

        <template v-else>
            <button class="text-orange-500 mr-0 ml-auto">Edit</button>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center">
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="w-28 h-28 bg-gray-200 shrink-0 rounded-full flex items-center justify-center relative"
                            >
                                <user-icon class="text-neutral-400" />

                                <verified-icon
                                    class="absolute -bottom-2 text-blue-700"
                                />
                            </div>

                            <div
                                class="flex flex-col items-center text-center gap-2"
                            >
                                <h2 class="text-2xl text-neutral-700 font-bold">
                                    {{ user.name }}
                                </h2>
                                <p class="text-sm text-neutral-500 font-normal">
                                    {{ user.profession ?? 'No profession' }}
                                </p>
                                <p
                                    class="text-sm text-neutral-500 font-normal flex items-center gap-1"
                                >
                                    {{user.data?.genre_focus.join(' & ') ?? 'No genre focus' }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center max-w-full overscroll-auto"
                        >
                            <button
                                v-for="(tab, tabID) in tabs"
                                :key="tabID"
                                :class="[
                                    activeTab == tabID
                                        ? 'text-red-600 border-red-600'
                                        : 'text-neutral-500 border-neutral-300',
                                    tabID == 0
                                        ? 'pr-5'
                                        : tabID == tabs.length - 1
                                          ? 'pl-5'
                                          : 'px-5',
                                ]"
                                class="text-sm font-semibold whitespace-nowrap select-none py-1 border-b"
                                @click="activeTab = tabID"
                            >
                                {{ tab }}
                            </button>
                        </div>

                        <profile-tab
                            v-if="activeTab == 0"
                            :user="user"
                        />
                        <template v-if="activeTab == 0">
                            <div
                                class="flex flex-col gap-3 text-left items-start w-full"
                            >
                                <div
                                    class="flex items-center justify-between w-full"
                                >
                                    <h4
                                        class="text-sm text-neutral-700 font-bold"
                                    >
                                        Your stories
                                    </h4>

                                    <button
                                        class="text-sm text-black font-normal opacity-50"
                                        @click="activeTab = 1"
                                    >
                                        See all
                                    </button>
                                </div>
                                <div
                                    v-if="user.stories?.length"
                                    class="flex flex-col gap-4 w-full"
                                >
                                    <StoryCard
                                        v-for="(story, storyID) in user.stories"
                                        :key="storyID"
                                        :story="story"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="w-full p-1 bg-zinc-200 rounded-lg"
                                >
                                    <div
                                        class="flex flex-col items-center gap-2 rounded-lg w-full p-4 border border-dashed border-gray-400"
                                    >
                                        <div
                                            class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-gray-400"
                                        >
                                            <plus-icon class="text-white" />
                                        </div>
                                        <span
                                            class="text-sm text-gray-400 font-normal"
                                        >
                                            Start building your first story
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex flex-col gap-3 text-left items-start w-full"
                            >
                                <div
                                    class="flex items-center justify-between w-full"
                                >
                                    <h4
                                        class="text-sm text-neutral-700 font-bold"
                                    >
                                        Earned achievements
                                    </h4>

                                    <button
                                        class="text-sm text-black font-normal opacity-50"
                                        @click="activeTab = 2"
                                    >
                                        See all
                                    </button>
                                </div>
                                <div
                                    class="flex gap-8 items-start max-w-full overflow-auto"
                                >
                                    <AchievementCard
                                        v-for="(
                                            achievement, achievementID
                                        ) in user.achievements"
                                        :key="achievementID"
                                        :item="achievement"
                                    />
                                </div>
                            </div>
                        </template>

                        <stories-tab
                            v-if="activeTab == 1"
                            :user="user"
                        />

                        <achievements-tab
                            v-if="activeTab == 2"
                            :achievements="user.achievements"
                        />
                        <div
                            class="w-full bg-white px-4 pb-4 fixed bottom-0 left-0"
                        >
                            <button
                                class="py-4 w-full rounded-full items-center justify-center bg-red-600 text-white text-base font-semibold"
                            >
                                Create an account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import StoryCard from '@/components/StoryCard.vue'
import AchievementCard from '@/components/AchievementCard.vue'

import LoadingTab from '@/components/LoadingTab.vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'

import UserIcon from '@/components/icons/UserIcon.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'
import axios from 'axios'
import { SuccessResponse } from '@/types/responses'
import User from '@/types/user'

const loading = ref(true)

const activeTab = ref(0)
const tabs = ['My profile', 'My stories', 'My achievements']
const user = ref<User | null>(null)

onMounted(() => {
    loading.value = true
    axios
        .get<
            SuccessResponse<{
                summary: User
            }>
        >('/api/v1/onboarding/summary')
        .then((response) => {
            user.value = response.data.data.summary
        })
        .finally(() => {
            loading.value = false
        })
})
</script>
<style scoped></style>
