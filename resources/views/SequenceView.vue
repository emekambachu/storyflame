<template>
    <div class="pb-6 flex flex-col gap-6">
        <div
            class="pt-6 flex flex-col gap-8 w-full"
            :class="data?.image?.path ? 'pb-4' : ''"
            :style="`background: ${
                data?.image?.path
                    ? `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, #000 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url(${data.image.path}) lightgray 50% / cover no-repeat;`
                    : 'transparent'
            }`"
        >
            <button
                class="flex items-center gap-2 px-4"
                :class="data?.image?.path ? 'text-pure-white' : 'text-black'"
            >
                <chevron-icon />
                <span class="text-lg font-normal">Back</span>
            </button>

            <div class="flex flex-col gap-1.5 px-4">
                <span
                    class="text-sm font-semibold opacity-70 uppercase"
                    :class="data?.image?.path ? 'text-white' : 'text-black'"
                >
                    {{ data.story.name }}
                </span>

                <div class="flex flex-col gap-3 w-full">
                    <h1
                        class="font-bold text-xl"
                        :class="data?.image?.path ? 'text-white' : 'text-black'"
                    >
                        {{ data.name }}
                    </h1>
                    <div class="flex items-center gap-2">
                        <span
                            v-for="(genre, genreID) in data.genres"
                            :key="genreID"
                            :class="
                                data?.image?.path ? 'text-white' : 'text-black'
                            "
                            class="text-sm font-normal opacity-40 gap-1.5 flex items-center"
                        >
                            <span v-if="genreID !== 0"><point-icon /></span>
                            {{ genre }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="flex items-center relative">
            <div
                v-for="(tab, tabID) in tabs"
                :key="tabID"
                :class="
                    tabID == selectedTab
                        ? 'text-red-600 border-red-600'
                        : 'text-neutral-500 border-neutral-300'
                "
                class="mx-4 text-base font-normal border-b-2 relative z-10"
                @click="selectedTab = tabID"
            >
                {{ tab }}
            </div>
            <div class="w-full h-[2px] bg-neutral-300 absolute bottom-0 z-5" />
        </div>
        <sequence-details-tab :data="data" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import PointIcon from '@/components/icons/PointIcon.vue'
import ChevronIcon from '@/components/icons/ChevronIcon.vue'

import SequenceDetailsTab from '@/components/SequenceDetailsTab.vue'

const selectedTab = ref(0)
const tabs = ['Details', 'Progress', 'Elements', 'Script']

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
</script>

<style scoped></style>
