<template>
    <div class="flex w-full flex-col gap-2 bg-white">
        <tab-layout
            :tabs="[
                {
                    title: 'Plot',
                    template: 'plot',
                },
                {
                    title: 'Characters',
                    template: 'characters',
                },
                {
                    title: 'Sequences',
                    template: 'sequences',
                },
                {
                    title: 'Settings',
                    template: 'settings',
                },
            ]"
            :no-animation="true"
            class="z-0 !gap-0"
            :scroll-to-page-section="true"
        >
            <template #sticky>
                <tab-layout-tabs />
            </template>
            <tab-layout-view>
                <template #plot>
                    <story-progress-tab-card
                        v-if="story?.plot"
                        :card="story.plot"
                    />
                </template>
                <template #characters>
                    <story-progress-tab-card
                        v-if="story?.characters_list"
                        :card="story.characters_list"
                    />
                </template>
                <template #sequences>
                    <story-progress-tab-card
                        v-if="story?.sequences"
                        :card="story.sequences"
                    />
                </template>
                <template #settings>
                    <story-progress-tab-card
                        v-if="story?.settings"
                        :card="story.settings"
                    />
                </template>
            </tab-layout-view>
        </tab-layout>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import TabLayoutView from '@/components/ui/TabLayoutView.vue'
import StoryProgressTabCard from '@/components/cards/StoryProgressTabCard.vue'

import TabLayout from '@/components/TabLayout.vue'
import { Story } from '@/types/story'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>

<style scoped></style>
