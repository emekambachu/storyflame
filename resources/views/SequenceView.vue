<template>
    <page-navigation-layout
        fixed
        no-back-text
        transparent
    >
        <tab-layout
            :tabs="[
                {
                    title: 'Details',
                    template: 'details',
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
                    title: 'Script',
                    template: 'script',
                },
            ]"
            collapse-header-height="120"
            header-height="325"
        >
            <default-element-header :title="sequence.name" />
            <template #details>
                <sequence-details-tab :data="data" />
            </template>
        </tab-layout>
        <div class="flex flex-col gap-6 pb-6"></div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import SequenceDetailsTab from '@/components/SequenceDetailsTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
import { useQuery } from '@tanstack/vue-query'
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { getSequence } from '@/utils/endpoints'

const data = {
    name: 'Arrival of King Robert and the Royal Family',
    story: {
        name: 'Game of Thrones',
    },
    image: {
        path: 'https://picsum.photos/900',
    },
    genres: ['Fantasy', 'Drama', 'Adventure', 'Political Intrigue'],

    kickstart:
        'Kickstart the main political plot by bringing the royal family to Winterfell, highlighting the close yet strained relationship between the Starks and the Baratheons.',
    drives_scene:
        'King Robert drives the sequence by his actions and decisions, from the moment of his arrival to his insistence on visiting the crypts.',
    goals: [
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 1,
        },
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 2,
        },
    ],
    conflict_sources: [
        'Political Obligations vs. Personal Desires: Ned Stark faces the tension between his duty to the king and his desire to stay in the North away from the politics of the capital.',
        'Past Relationships vs. Present Realities: The reminiscence of past friendships contrasts with current political necessities and personal changes.',
        'Expectations vs. Reality: The grandeur and behavior of the royal family may not meet the expectations of the Stark family, highlighting differences and brewing discontent.',
    ],
}

const route = useRoute()
const storyId = computed(() => route.params.story)
const sequenceId = computed(() => route.params.sequence)

const { data: sequence } = useQuery({
    queryKey: ['sequence', sequenceId.value],
    queryFn: () => getSequence(storyId.value, sequenceId.value),
    select(data) {
        return data.data
    },
})
</script>

<style scoped></style>
