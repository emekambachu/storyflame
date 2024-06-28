<template>
    <page-navigation-layout fixed transparent>
        <div class="mx-auto flex h-full grow w-full max-w-md flex-col items-center">
            <!--            <button class="text-orange-500 mr-0 ml-auto">Edit</button>-->
            <div
                v-if="user"
                class="flex w-full h-full grow flex-col items-center gap-8"
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
                        <stories-tab :user="user" />
                    </template>
                    <template #achievements>
                        <achievements-tab :achievements="user.achievements" />
                    </template>
                </tab-layout>
            </div>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import ProfileTab from '@/components/ProfileTab.vue'
import StoriesTab from '@/components/StoriesTab.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import TabLayout from '@/components/TabLayout.vue'
import UserProfileHeader from '@/components/UserProfileHeader.vue'
import { useAuthStore } from '@/stores/auth'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

const loading = ref(true)
// const user = ref<User | null>(null)
const { user, getUser } = useAuthStore()

onMounted(() => {
    // loading.value = true
    getUser()
    // axios
    //     .get<SuccessResponse<User>>('/api/v1/auth/user')
    //     .then((response) => {
    //         user.value = response.data.data
    //     })
    //     .finally(() => {
    //         loading.value = false
    //     })
})
</script>
<style scoped></style>
