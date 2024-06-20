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
            <template #plot>
                <story-progress-tab-card :card="story.plot" />
            </template>
            <template #characters>
                <story-progress-tab-card :card="story.characters_list" />
            </template>
            <template #sequences>
                <story-progress-tab-card :card="story.sequences" />
            </template>
            <template #settings>
                <story-progress-tab-card :card="story.settings" />
            </template>
        </tab-layout>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import StoryProgressTabCard from '@/components/cards/StoryProgressTabCard.vue'

import TabLayout from '@/components/TabLayout.vue'
import { Story } from '@/types/story'

const props = defineProps({
    story: {
        // type: Object as PropType<Story>,
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
