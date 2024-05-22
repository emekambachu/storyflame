<template>
    <div class="flex flex-col gap-4 pb-8">
        <div class="bg-zinc-900 flex flex-col gap-8 py-8 px-4">
            <p
                v-if="character?.quote"
                class="font-semibold font-decorative italic text-2m text-center text-slate-500"
            >
                “{{ character.quote }}”
            </p>

            <div class="flex flex-col gap-1">
                <span class="text-sm text-slate-500 font-normal">
                    {{ character.role }}
                </span>
                <div class="flex items-center justify-between w-full">
                    <h1 class="text-2xl text-pure-white font-bold">
                        {{ character.name }}
                    </h1>

                    <flame-icon :priority="character.progress" />
                </div>
                <p class="w-full flex items-center gap-2">
                    <span
                        v-for="(feature, featureID) in character.features"
                        :key="featureID"
                        class="text-sm text-slate-500 font-normal flex items-center gap-2"
                    >
                        <point-icon v-if="featureID !== 0" />
                        {{ feature }}
                    </span>
                </p>

                <p
                    v-if="character?.description"
                    class="text-sm text-slate-400 font-normal mt-1"
                >
                    {{ character.description }}
                </p>
            </div>
        </div>

        <tab-layout
            :tabs="[
                { title: 'About', template: 'about' },
                { title: 'Progress', template: 'progress' },
                { title: 'Dialogue', template: 'dialogue' },
                { title: 'Achievements', template: 'achievements' },
            ]"
        >
            <template #about>
                <character-about-tab :character="character" />
            </template>
            <template #progress>
                <div>Nothing here, please check tab later</div>
            </template>
            <template #dialog>
                <div>Nothing here, please check tab later</div>
            </template>
            <template #achievements>
                <achievements-tab :achievements="character.achievements" />
            </template>
        </tab-layout>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import FlameIcon from '@/components/icons/FlameIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

import TabLayout from '@/components/TabLayout.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import CharacterAboutTab from '@/components/CharacterAboutTab.vue'

const character = {
    name: 'Tyrion Lannister',
    progress: 2,
    description:
        'Has a deep-seated desire for love and acceptance, despite his cynical exterior',

    role: 'Protagonist',
    quote: 'I have a tender spot in my heart for cripples, bastards, and broken things',

    features: ['The Patriarch', 'Noble Top Enforcer'],

    goals: [
        'Be respected for his intellect',
        'Find his place in the world',
        'Protect his family',
    ],

    cliches: [
        {
            title: 'The "Wise Dwarf" Trope',
            description:
                'This cliche portrays dwarves as inherently wise, intelligent, or possessing mystical knowledge, often used as a plot device to guide the protagonist.',
        },

        {
            title: 'The "Comic Relief Dwarf" Trope',
            description:
                ' In this cliche, dwarf characters are used primarily for comic relief, with their humor often derived from their physical appearance or stereotypical traits associated with dwarfism.',
        },
    ],

    backstory:
        'Experienced trauma and loss due to his mother`s death in childbirth and his father`s resentment; used his intellect and wit to navigate the challenges of being a dwarf in a society that values physical strength',
    strengths: [
        'High Openness',
        'Loyal to the loved ones',
        'Adaptable',
        'Empathetic',
    ],

    trairs: ['Highly intelligent'],

    achievements: [
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement One',
            completed_at: '2023-05-10',
            progress: 100,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Two',
            completed_at: null,
            progress: 50,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Three',
            completed_at: null,
            progress: 0,
        },
        {
            icon: 'https://picsum.photos/900',
            title: 'Achievement Four',
            completed_at: null,
            progress: 75,
        },
    ],
}
</script>

<style scoped></style>
