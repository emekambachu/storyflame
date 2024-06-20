<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section class="bg-white px-4 py-4">
            <template #title>
                <title-with-link
                    title="Story Points"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <story-tab-section-card
                v-for="(section, sectionID) in story.story_info"
                :key="sectionID"
                :title="section.title"
                :description="section.description"
                :list="section.list"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import StoryTabSectionCard from '@/components/cards/StoryTabSectionCard.vue'

import PointIcon from '@/components/icons/PointIcon.vue'
import { Story } from '@/types/story'

const props = defineProps({
    story: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
