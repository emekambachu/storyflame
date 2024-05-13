<template>
    <div
        class="w-full min-h-screen flex flex-col items-center pb-24 max-w-md mx-auto"
    >
        <!--            <button class="text-orange-500 mr-0 ml-auto">Edit</button>-->
        <div v-if="user" class="flex flex-col items-center gap-8 w-full">
            <tab-layout
                :tabs="[
                    {
                        title: 'My profile',
                        template: 'profile',
                    },
                    {
                        title: 'My stories',
                        template: 'stories',
                    },
                    {
                        title: 'My achievements',
                        template: 'achievements',
                    },
                ]"
            >
                <user-profile-header :user="user" />
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
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import axios from 'axios'
import { SuccessResponse } from '@/types/responses'
import User from '@/types/user'
import TabLayout from '@/components/TabLayout.vue'
import UserProfileHeader from '@/components/UserProfileHeader.vue'

const loading = ref(true)
const user = ref<User | null>(null)

onMounted(() => {
    loading.value = true
    axios
        .get<SuccessResponse<User>>('/api/v1/auth/user')
        .then((response) => {
            user.value = response.data.data
        })
        .finally(() => {
            loading.value = false
        })
})
</script>
<style scoped></style>
