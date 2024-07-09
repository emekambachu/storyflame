<template>
    <page-navigation-layout
        fixed
        no-back-text
        transparent
    >
        <div
            class="mx-auto flex h-full w-full max-w-md grow flex-col items-center"
        >
            <!--            <button class="text-orange-500 mr-0 ml-auto">Edit</button>-->
            <div
                v-if="user"
                class="flex h-full w-full grow flex-col items-center gap-8"
            >
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
                    collapse-header-height="100"
                    header-height="200"
                >
                    <user-profile-header :user="user" />
                    <template #profile>
                        <profile-tab :user="user" />
                    </template>
                    <template #stories>
                        <profile-stories-tab :user="user" />
                    </template>
                    <template #achievements>
                        <profile-achievements-tab
                            :achievements="user?.achievements"
                        />
                    </template>
                </tab-layout>
            </div>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import ProfileTab from '@/components/profile/ProfileTab.vue'
import ProfileStoriesTab from '@/components/profile/ProfileStoriesTab.vue'
import ProfileAchievementsTab from '@/components/profile/ProfileAchievementsTab.vue'
import TabLayout from '@/components/TabLayout.vue'
import UserProfileHeader from '@/components/headers/UserProfileHeader.vue'
import { useAuthStore } from '@/stores/auth'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import { useUser } from '@/composables/query/user'

const { data: user } = useUser()
</script>
<style scoped></style>
