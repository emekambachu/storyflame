<template>
    <div class="flex flex-col gap-4 pb-20">
        <div
            class="flex flex-col items-center gap-2 pt-8 py-4 bg-zinc-900 px-4"
        >
            <flame-icon
                class="!w-8 !h-8"
                flameClass="w-8 h-8"
            />
            <span class="text-xs text-pure-white font-bold opacity-50">
                {{ data.type }}
            </span>
            <h1 class="text-2xl text-pure-white font-bold">
                {{ data.title }}
            </h1>

            <p class="text-sm text-slate-500 text-center font-normal">
                {{ data.description }}
            </p>
        </div>
        <tab-layout
            :tabs="[
                {
                    title: 'About',
                    template: 'about',
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
                    title: 'Achievements',
                    template: 'achievements',
                },
            ]"
        >
            <template #about>
                <call-to-action-cards-list
                    class="px-4"
                    title="Continue where you left off"
                    :items="items"
                />
                <plot-about-tab :data="data" />
            </template>
            <template #characters>
                <call-to-action-cards-list
                    class="px-4"
                    title="Continue where you left off"
                    :items="items"
                />
                <plot-characters-tab :data="data" />
            </template>
            <template #sequences>
                <call-to-action-cards-list
                    class="px-4"
                    title="Continue where you left off"
                    :items="items"
                />
                <plot-sequences-tab :data="data" />
            </template>

            <template #achievements>
                <call-to-action-cards-list
                    class="px-4"
                    title="Continue where you left off"
                    :items="items"
                />
                <plot-achievements-tab :data="data" />
            </template>
        </tab-layout>
    </div>
</template>

<script setup lang="ts">
import TabLayout from '@/components/TabLayout.vue'
import PlotAboutTab from '@/components/tabs/PlotAboutTab.vue'
import PlotSequencesTab from '@/components/tabs/PlotSequencesTab.vue'
import PlotCharactersTab from '@/components/tabs/PlotCharactersTab.vue'
import PlotAchievementsTab from '@/components/tabs/PlotAchievementsTab.vue'
import CallToActionCardsList from '@/components/CallToActionCardsList.vue'

import FlameIcon from '@/components/icons/FlameIcon.vue'

const items = [
    {
        image: { path: 'https://picsum.photos/32' },
        title: 'Write you story',
        description: 'Provide basic information about your story',
    },
]

const data = {
    type: 'Main plot',
    title: 'The Battle of Blackwater',
    description:
        'The Battle of Blackwater is a pivotal clash for the Iron Throne. Stannis Baratheon besieges King`s Landing, facing off against defenders led by Tyrion Lannister.',
    image: { path: '' },

    breaking_point:
        'Stannis Baratheon`s ambitious campaign to claim the Iron Throne, King`s Landing`s frantic preparations for defense under Tyrion Lannister`s leadership, a dramatic naval battle fueled by wildfire, and the unexpected arrival of reinforcements led by Tywin Lannister, ultimately resulting in a decisive victory for the defenders and thwarting Stannis`s invasion.',
    associated_characters: [
        {
            title: 'Daenerys Targaryen',
            description:
                '- trives to reclaim the Iron Throne and establish herself as the rightful ruler of the Seven Kingdoms.',
        },
        {
            title: 'Cersei Lannister',
            description:
                '- seeks to consolidate and maintain power for herself and her family.',
        },
    ],

    main_protagonist: 'Tyrion Lannister',
    goal: 'Tyrion`s primary goal is to defend King`s Landing and secure victory against Stannis Baratheon`s forces, thereby preserving the rule of House Lannister and maintaining his position of power and influence within the city.',
    twists_turns:
        'The deployment of wildfire by Tyrion Lannister serves as a surprising and game-changing tactic that turns the tide of the battle in favor of the defenders.',
    exploration:
        'Throughout the battle, Tyrion explores his leadership abilities and strategic acumen, demonstrating his resourcefulness and resilience in the face of adversity. He navigates the complexities of wartime politics and military tactics, forging alliances and making difficult decisions to ensure the city`s survival.',
    difficulties: [
        'Doubts and fears about his ability to protect King`s Landing and his loved ones',
        'Moral dilemmas and ethical challenges',
    ],
    resolution:
        'The conflict is resolved with the decisive victory of the defenders of King`s Landing, securing the city against Stannis Baratheon`s forces and preserving the rule of House Lannister on the Iron Throne.',
    end: 'Tyrion`s efforts culminate in the successful defense of King`s Landing and the defeat of Stannis Baratheon`s forces. Despite the immense challenges he faces, Tyrion`s leadership and strategic brilliance play a crucial role in securing victory for the city, ensuring the survival of House Lannister and his own continued prominence within the realm.',

    goals: [
        {
            title: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 1,
        },
        {
            title: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 2,
        },
    ],
    themes: [
        {
            readiness: 1,
            type: 'major',
            title: 'Power',
            description:
                'The quest for power and the lengths characters will go to attain and maintain it.',
        },
    ],
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
    achievements_summary: [
        { title: 'Earned', value: 10 },
        { title: 'progress', value: 0 },
        { title: 'Up to next', value: 0 },
    ],
}
</script>

<style scoped></style>
