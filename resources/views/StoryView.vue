<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-6 pb-6">
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
            >
                <!--                <story-header :story="story" />-->
                <header-animated
                    collapse-header-height="80"
                    header-height="478"
                >
<!--                    <default-element-header height="478px" />-->
                    <story-header v-if="story" :story="story"/>
                    <template #sticky>
                        <tab-layout-tabs />
                    </template>
                </header-animated>
                <template #about>
                    <story-about-tab :story="story" />
                </template>
                <template #drafts>
                    <story-drafts-tab :drafts="story.drafts" />
                </template>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import StoryAboutTab from '@/components/StoryAboutTab.vue'
import StoryDraftsTab from '@/components/StoryDraftsTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import { useRoute } from 'vue-router'
import { useQuery } from '@tanstack/vue-query'
import { getStory } from '@/utils/endpoints'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import StoryHeader from '@/components/StoryHeader.vue'

const route = useRoute()
const storyId = computed(() => route.params.story)

const { data: story } = useQuery({
    queryKey: ['story', storyId.value],
    queryFn: () => getStory(storyId.value),
    select(data) {
        return data.data
    },
})
</script>

<style scoped></style>
