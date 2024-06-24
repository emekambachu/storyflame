<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-4 pb-8">
            <tab-layout
                :tabs="[
                    { title: 'About', template: 'about' },
                    { title: 'Progress', template: 'progress' },
                    { title: 'Dialogue', template: 'dialogue' },
                    { title: 'Achievements', template: 'achievements' },
                ]"
            >
                <header-animated
                    collapse-header-height="68"
                    header-height="260"
                >
                    <character-header :character="character" />
                    <template #sticky>
                        <tab-layout-tabs />
                    </template>
                </header-animated>

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
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import FlameIcon from '@/components/icons/FlameIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

import TabLayout from '@/components/TabLayout.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import CharacterAboutTab from '@/components/CharacterAboutTab.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import CharacterHeader from '@/components/headers/CharacterHeader.vue'

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
