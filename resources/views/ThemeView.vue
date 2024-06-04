<template>
    <div class="flex flex-col gap-8 py-8 px-4">
        <button class="flex items-center gap-2 text-black">
            <chevron-icon />
            <span class="text-lg font-normal">Back</span>
        </button>

        <div class="flex flex-col items-center">
            <flame-icon
                class="!w-8 !h-8"
                flameClass="w-8 h-8"
            />
            <h1 class="text-2xl text-neutral-700 font-bold">
                {{ data.title }}
            </h1>
            <span class="text-xs text-black font-bold opacity-50">
                {{ data.type }}
            </span>
            <p class="text-sm text-black text-center font-normal opacity-50">
                {{ data.description }}
            </p>
        </div>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">
                    Associated characters
                </h4>
            </template>

            <text-list :items="data.associated_characters" />
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Common сlichés:"
                    class="!p-0"
                    titleClass="text-lg text-black font-bold"
                />
            </template>
            <text-list :items="data.common_cliches" />
        </title-section>

        <title-section>
            <template #title>
                <title-with-link
                    title="Achievements"
                    class="!p-0"
                    titleClass="text-lg text-black font-bold"
                />
            </template>

            <div class="flex items-start gap-2 justify-between w-full">
                <achievement-card
                    v-for="(
                        achievement, achievementID
                    ) in data.achievements.slice(0, 3)"
                    :key="achievementID"
                    :item="achievement"
                    class="h-full"
                />
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TextList from '@/components/TextList.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import AchievementCard from '@/components/cards/AchievementCard.vue'

import FlameIcon from '@/components/icons/FlameIcon.vue'
import ChevronIcon from '@/components/icons/ChevronIcon.vue'

const data = {
    progres: 1,
    title: 'Power',
    type: 'Major theme',
    description:
        'The quest for power and the lengths characters will go to attain and maintain it.',
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

    common_cliches: [
        {
            title: 'Rise of the Underdog:',
            description:
                'Stories where marginalized individuals overcome adversity and rise to power through determination and resilience.',
        },
        {
            title: 'Unity and Cooperation:',
            description:
                'Stories where marginalized individuals overcome adversity and rise to power through determination and resilience.',
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
}
</script>

<style scoped></style>
