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
                        title: 'Expanded',
                        template: 'expanded',
                    },
                    {
                        title: 'Condensed',
                        template: 'condensed',
                    },
                ]"
                :no-animation="true"
                menu-btn-class="w-full text-base text-stone-500 px-4 py-3 border-none"
                menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-4 py-3 border-none"
                menu-container-class="w-full flex rounded-lg bg-stone-100"
                menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3"
            >
                <outline-header :outline="outline" />
                <template #expanded>
                    <div class="flex flex-col gap-3 px-3">
                        <sequence-card
                            v-for="(sequence, sequenceID) in outline.sequences"
                            :key="sequenceID"
                            :card="sequence"
                        />
                    </div>
                </template>
                <template #condensed>
                    <div class="flex flex-col gap-3 px-3">
                        <sequence-card
                            v-for="(sequence, sequenceID) in outline.sequences"
                            :key="sequenceID"
                            :card="sequence"
                            :expanded="false"
                        />
                    </div>
                </template>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
// import { getStory } from '@/utils/endpoints'
// import { useQuery } from '@tanstack/vue-query'

import OutlineHeader from '@/components/OutlineHeader.vue'

import TabLayout from '@/components/TabLayout.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import SequenceCard from '@/components/cards/SequenceCard.vue'

// import OutlineExpandedTab from '@/components/OutlineExpandedTab.vue'
// import OutlineCondensedTab from '@/components/OutlineCondensedTab.vue'

const route = useRoute()
// const outlineId = computed(() => route.params.outline)

// const { data: outline } = useQuery({
//     queryKey: ['outline', outlineId.value],
//     queryFn: () => getOutline(outlineId.value),
//     select(data) {
//         return data.data
//     },
// })

const outline = {
    title: 'Some title here',
    story: 'Game of Thrones',
    type: 'TV Pilot',
    format: 'Outline',

    progress: 80,
    image: {
        path: 'https://picsum.photos/900',
    },

    achievements: [
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            id: 2,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            id: 3,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 4,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
        {
            id: 1,
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: null,
            progress: 0,
        },
    ],
    sequences: [
        {
            type: 'Turning point Sequence',
            number: 1,
            progress: 1,
            title: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
            paragraphs: ['The audience should be surprised and shocked.'],
        },
        {
            type: 'Turning point Sequence',
            number: 2,
            progress: 40,
            title: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
        },
    ],
}
</script>

<style scoped></style>
