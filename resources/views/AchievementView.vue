<template>
    <div class="flex w-full flex-col pb-8">
        <div
            class="flex flex-col items-center px-4 py-8"
            :style="`background: linear-gradient(146deg, rgba(0, 0, 0, 0.00) 13.78%, rgba(0, 0, 0, 0.25) 87.57%), ${data.color};
        background-blend-mode: plus-darker, normal;`"
        >
            <image-component
                :src="data.image.path"
                alt="poster"
                class="h-20 w-16 shrink-0 object-contain"
            />
            <span class="mt-2 text-sm text-white opacity-50">
                {{ data.type }}
            </span>
            <h1 class="mt-2 text-xl font-bold text-white">{{ data.title }}</h1>
            <p class="mb-4 mt-2 text-sm text-white opacity-50">
                {{ data.subtitle }}
            </p>

            <hr class="mb-4 w-full max-w-44 text-white opacity-50" />

            <div class="flex w-full items-start justify-between">
                <div
                    v-for="(item, itemID) in itemsArray"
                    :key="itemID"
                    class="flex w-full max-w-20 flex-col items-center"
                >
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                        style="background: rgba(255, 255, 255, 0.3)"
                    >
                        <component
                            :is="item.icon"
                            class="text-white"
                        />
                    </div>
                    <h6 class="mt-2 text-base font-bold text-white">
                        {{ item.value }}
                    </h6>
                    <span
                        class="text-xs font-semibold capitalize text-white opacity-50"
                    >
                        {{ item.title }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col gap-2 bg-slate-100">
            <div class="bg-white p-4">
                <p class="max-w-[95%] text-center text-lg text-stone-950">
                    {{ data.description }}
                </p>
            </div>

            <title-section class="!gap-2 bg-white p-4">
                <template #title>
                    <h4 class="text-lg font-bold text-black">
                        Developed in this achievement
                    </h4>
                    <p
                        v-for="(paragraph, paragraphID) in data.developed"
                        :key="paragraphID"
                        class="text-sm text-slate-600"
                    >
                        {{ paragraph }}
                    </p>
                </template>
            </title-section>

            <title-section class="!gap-6 bg-white p-4">
                <template #title>
                    <title-with-link
                        title="Clarify some discrepancies"
                        class="!p-0"
                        title-class="text-lg text-black font-bold"
                    />
                </template>

                <div class="flex flex-col gap-3">
                    <discrepancies-card
                        v-for="(card, cardID) in data.discrepancies"
                        :key="cardID"
                        :card="card"
                    />
                </div>
            </title-section>

            <title-section class="!gap-2 bg-white p-4">
                <template #title>
                    <h4 class="text-lg font-bold text-black">
                        Impacted by this achievement
                    </h4>
                    <div
                        class="flex w-full max-w-full items-center gap-2 overflow-scroll"
                    >
                        <button
                            v-for="(tab, tabID) in Object.values(data.impacted)"
                            :key="tabID"
                            :class="
                                selectedTabID == tabID
                                    ? 'bg-stone-950 text-stone-100'
                                    : 'bg-stone-200 text-stone-700'
                            "
                            class="whitespace-nowrap rounded p-2 text-sm"
                            @click="selectedTabID = tabID"
                        >
                            {{ tab?.items?.length }} {{ tab.title }}
                        </button>
                    </div>

                    <hr class="w-full text-stone-300" />
                    <template v-if="selectedTabID == 0">
                        <character-card
                            v-for="(character, characterID) in data.impacted
                                ?.characters?.items"
                            :key="characterID"
                            :card="character"
                        />
                    </template>

                    <template v-if="selectedTabID == 1">
                        <sequences-card
                            v-for="(sequence, sequenceID) in data.impacted
                                ?.sequences?.items"
                            :key="sequenceID"
                            :card="sequence"
                        />
                    </template>

                    <template v-if="selectedTabID == 2">
                        <plot-card
                            v-for="(plot, plotID) in data.impacted?.plot?.items"
                            :key="plotID"
                            :card="plot"
                        />
                    </template>

                    <template v-if="selectedTabID == 3">
                        <theme-card
                            v-for="(theme, themeID) in data.impacted?.themes
                                ?.items"
                            :key="themeID"
                            :card="theme"
                        />
                    </template>
                </template>
            </title-section>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import ImageComponent from '@/components/ImageComponent.vue'

import PlotCard from '@/components/cards/PlotCard.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'
import SequencesCard from '@/components/cards/SequencesCard.vue'
import DiscrepanciesCard from '@/components/cards/DiscrepanciesCard.vue'

import ClockIcon from '@/components/icons/ClockIcon.vue'
import FlashIcon from '@/components/icons/FlashIcon.vue'
import VectorIcon from '@/components/icons/VectorIcon.vue'

const selectedTabID = ref<number>(0)

const data = {
    type: 'Character',
    color: '#10798E',
    title: 'Choices and Actions',
    subtitle: 'Revealing character through decisive moments',
    description:
        'Lucy`s choices and actions under pressure reveal her courage and compassion.',
    image: { path: 'https://picsum.photos/900' },
    statistic: {
        time: 3,
        elements: 14,
        discrepancies: 3,
    },
    developed: [
        'Lucy`s quest to safeguard the timepiece from falling into the wrong hands and protect the integrity of history creates a compelling central conflict. The dire consequences if she fails and the complex web of opposing forces raise the stakes and tension.',
        'Lucy`s selfless decision to sacrifice the chance to see her beloved grandmother to preserve the timeline demonstrates her growing wisdom and commitment to the greater good. Her acts of compassion towards others despite the risks show her strong moral compass.',
    ],
    discrepancies: [
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
    ],
    impacted: {
        characters: {
            title: 'Characters',
            items: [
                {
                    role: 'Main major',
                    title: 'Daenerys Targaryen',
                    type: 'Protagonist',

                    description:
                        'Gain independence and reclaim her ancestral throne.',
                },
                {
                    role: 'Main major',
                    title: 'Daenerys Targaryen',
                    type: 'Protagonist',
                    story: 'Game of Thrones',
                    episode: 'Episode: 12o3j sdofijdsf woejwfo',
                    description:
                        'Gain independence and reclaim her ancestral throne.',
                },
            ],
        },
        sequences: {
            title: 'Sequences',
            items: [
                {
                    number: 1,
                    readiness: 1,
                    title: 'Red Wedding',
                    description:
                        'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
                },
            ],
        },
        plot: {
            title: 'Plot',
            items: [
                {
                    title: 'A Study in Pink',
                    subtitle: 'main plot',
                    description:
                        'Sherlock Holmes and Dr. John Watson meet for the first time and investigate a series of mysterious deaths linked by the color pink, leading them into a deadly game with a clever serial killer.',
                },
            ],
        },
        themes: {
            title: 'Themes',
            items: [
                {
                    type: 'major',
                    readiness: 2,
                    title: 'Power',
                    description:
                        'The quest for power and the lengths characters will go to attain and maintain it.',
                },
            ],
        },
    },
}

const itemsArray = [
    {
        title: 'Minutes',
        value: data?.statistic?.time ?? 0,
        icon: ClockIcon,
    },
    {
        title: 'elements',
        value: data?.statistic?.elements ?? 0,
        icon: FlashIcon,
    },
    {
        title: 'achievements',
        value: data?.statistic?.discrepancies ?? 0,
        icon: VectorIcon,
    },
]
</script>

<style scoped></style>
