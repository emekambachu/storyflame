<template>
    <div class="relative flex w-full flex-col items-center">
        <div class="flex w-full flex-col gap-3 bg-neutral-950 px-4 py-8">
            <div class="flex w-full items-center justify-between">
                <logo-icon />
                <router-link
                    :to="{ name: 'profile' }"
                    class="h-8 w-8 shrink-0 rounded-full bg-white"
                ></router-link>
            </div>
<!--            <p class="text-sm font-medium uppercase text-slate-400">-->
<!--                {{ data.smth }}-->
<!--            </p>-->
        </div>
        <nav-tabs
            v-model="activeTab"
            :tabs="tabs"
            class="sticky top-12 z-10"
        />

        <main class="z-1 relative w-full bg-white px-2 pb-6 md:px-4">
            <component
                :is="activeTabComponent"
                v-if="user"
                :user="user"
            />
        </main>
    </div>
</template>

<script lang="ts" setup>
import { computed, provide, ref } from 'vue'
import { useUser } from '@/composables/query/user'
import NavTabs from '@/components/ui/NavTabs.vue'
import LogoIcon from '@/components/icons/LogoIcon.vue'
import ProfileTab from '@/components/profile/ProfileTab.vue'
import StoriesTab from '@/components/profile/StoriesTab.vue'
import AchievementsTab from '@/components/profile/AchievementsTab.vue'
import MembershipTab from '@/components/profile/MembershipTab.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import { tabLayoutActiveTabInjection } from '@/types/injection'

const { data: user, isLoading, isError } = useUser()

const tabs = [
    { title: 'My profile', key: 'profile' },
    { title: 'My stories', key: 'stories' },
    { title: 'My achievements', key: 'achievements' },
    { title: 'Membership', key: 'membership' },
]

const activeTab = ref(tabs[0].key)
provide(tabLayoutActiveTabInjection, activeTab)

const activeTabComponent = computed(() => {
    switch (activeTab.value) {
        case 'profile':
            return ProfileTab
        case 'stories':
            return StoriesTab
        case 'achievements':
            return AchievementsTab
        case 'membership':
            return MembershipTab
        default:
            return ProfileTab
    }
})

const data = {
    smth: '[something celebratory]',
}
</script>
