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
                    :tabs="tabs"
                    class="gap-4"
                    collapse-header-height="100"
                    header-height="200"
                >
                    <header-animated>
                        <user-profile-header :user="user" />
                        <template #sticky>
                            <tab-layout-tabs />
                        </template>
                    </header-animated>
                    <tab-layout-view>
                        <template #profile>
                            <profile-tab :user="user" />
                        </template>
                        <template #stories>
                            <stories-tab :user="user" />
                        </template>
                        <template #achievements>
                            <achievements-tab
                                :achievements="user.achievements"
                            />
                        </template>
                        <template #membership>
                            <user-profile-subscription-tab
                                :user="user"
                            />
                        </template>
                    </tab-layout-view>
                </tab-layout>
            </div>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import TabLayout from '@/components/TabLayout.vue'
import UserProfileHeader from '@/components/UserProfileHeader.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import { useUser } from '@/composables/query/user'
import UserProfileSubscriptionTab from "@/components/userProfile/UserProfileSubscriptionTab.vue";

const { data: user } = useUser()

console.log('User Data:', user); // Log the user data
// console.log('Error:', error); // Log any errors
// console.log('Loading:', isLoading); // Log the loading state

const tabs = [
    { title: 'My profile', template: 'profile' },
    { title: 'My stories', template: 'stories' },
    { title: 'My achievements', template: 'achievements' },
    { title: 'Membership', template: 'membership' },
];

</script>
<style scoped></style>
