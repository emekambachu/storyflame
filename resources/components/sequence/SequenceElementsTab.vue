<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section
            v-if="data?.episodes"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top Episodes"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-2">
                <episode-card
                    v-for="(episode, episodeID) in data?.episodes"
                    :key="episodeID"
                    :card="episode"
                />
            </div>
        </title-section>

        <title-section
            v-if="data?.characters"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top characters"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-2">
                <character-card
                    v-for="(character, characterID) in data?.characters"
                    :key="characterID"
                    :card="character"
                />
            </div>
        </title-section>

        <title-section
            v-if="data?.themes"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top Themes"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-6">
                <theme-card
                    v-for="(theme, themeID) in data.themes"
                    :key="themeID"
                    :card="theme"
                />
            </div>
        </title-section>

        <title-section
            v-if="data?.plots"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top Plots"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-6">
                <plot-card
                    v-for="(plot, plotID) in data.plots"
                    :key="plotID"
                    :card="plot"
                />
            </div>
        </title-section>

        <title-section
            v-if="data?.settingsList"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top Plots"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-6">
                <setting-card
                    v-for="(setting, settingID) in data.settingsList"
                    :key="settingID"
                    :card="setting"
                />
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import PlotCard from '@/components/cards/PlotCard.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'
import EpisodeCard from '@/components/cards/EpisodeCard.vue'
import SettingCard from '@/components/cards/SettingCard.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
