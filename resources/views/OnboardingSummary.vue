<template>
    <div
        class="w-full min-h-screen flex flex-col items-center pt-10 pb-24"
    >
        <template v-if="loading || !user">
            <loading-tab class="w-full min-h-screen" />
        </template>

        <template v-else>
            <!--            <button class="text-orange-500 mr-0 ml-auto">Edit</button>-->
            <div class="flex flex-col items-center gap-8 w-full">
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-28 h-28 bg-gray-200 shrink-0 rounded-full flex items-center justify-center relative"
                    >
                        <user-icon class="text-neutral-400" />

                        <verified-icon
                            class="absolute -bottom-2 text-blue-700"
                        />
                    </div>

                    <div class="flex flex-col items-center text-center gap-2">
                        <h2 class="text-2xl text-neutral-700 font-bold">
                            {{ user.name }}
                        </h2>
                        <p class="text-sm text-neutral-500 font-normal">
                            {{ user.profession ?? 'No profession' }}
                        </p>
                        <p
                            class="text-sm text-neutral-500 font-normal flex items-center gap-1"
                        >
                            {{
                                user.data?.genre_focus.join(' & ') ??
                                'No genre focus'
                            }}
                        </p>
                    </div>
                </div>

                <tab-layout
                    :tabs="[
                        {
                            title: 'My profile',
                            template: 'profile'
                        },
                        {
                            title: 'My stories',
                            template: 'stories'
                        },
                        {
                            title: 'My achievements',
                            template: 'achievements'
                        }
                    ]"
                >
                    <template #profile>
                        <profile-tab :user="user" />
                    </template>
                    <template #stories>
                        <stories-tab :user="user" />
                    </template>
                    <template #achievements>
                        <achievements-tab :achievements="user.achievements" />
                    </template>
                </tab-layout>

                <!--                        <div-->
                <!--                            class="w-full bg-white px-4 pb-4 fixed bottom-0 left-0"-->
                <!--                        >-->
                <!--                            <button-->
                <!--                                class="py-4 w-full rounded-full items-center justify-center bg-red-600 text-white text-base font-semibold"-->
                <!--                            >-->
                <!--                                Create an account-->
                <!--                            </button>-->
                <!--                        </div>-->
            </div>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'

import LoadingTab from '@/components/LoadingTab.vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import axios from 'axios'
import { SuccessResponse } from '@/types/responses'
import User from '@/types/user'
import TabLayout from '@/components/TabLayout.vue'
import VerifiedIcon from '@/components/icons/VerifiedIcon.vue'
import UserIcon from '@/components/icons/UserIcon.vue'

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
