<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <template
            v-for="element in [
                'episodes',
                'characters',
                'themes',
                'sequences',
                'plots',
                'settings',
            ] as Array<StoryElementKey>"
        >
            <title-section
                v-if="story.elements[element]?.count"
                class="!gap-6 bg-white px-4 py-6"
            >
                <template #title>
                    <title-with-link
                        :title="`Top ${element}`"
                        class="!p-0"
                        title-class="text-lg text-black font-bold"
                    />
                </template>

                <div class="flex w-full flex-col gap-2">
                    <element-card
                        v-for="(elementData, episodeID) in story.elements[element].elements"
                        :key="episodeID"
                        :type="element"
                        :element="elementData"
                    />
                </div>
            </title-section>
        </template>

<!--        <title-section-->
<!--            v-if="story.elements.episodes?.count"-->
<!--            class="!gap-6 bg-white px-4 py-6"-->
<!--        >-->
<!--            <template #title>-->
<!--                <title-with-link-->
<!--                    class="!p-0"-->
<!--                    title="Top Episodes"-->
<!--                    title-class="text-lg text-black font-bold"-->
<!--                />-->
<!--            </template>-->

<!--            <div class="flex w-full flex-col gap-2">-->
<!--                <episode-card-->
<!--                    v-for="(episode, episodeID) in story.elements.episodes"-->
<!--                    :key="episodeID"-->
<!--                    :card="episode"-->
<!--                />-->
<!--            </div>-->
<!--        </title-section>-->

<!--        <title-section-->
<!--            v-if="story.elements.characters?.count"-->
<!--            class="!gap-6 bg-white px-4 py-6"-->
<!--        >-->
<!--            <template #title>-->
<!--                <title-with-link-->
<!--                    class="!p-0"-->
<!--                    title="Top characters"-->
<!--                    title-class="text-lg text-black font-bold"-->
<!--                />-->
<!--            </template>-->

<!--            <div class="flex w-full flex-col gap-2">-->
<!--                <character-card-->
<!--                    v-for="(character, characterID) in story.elements.characters-->
<!--                        .elements"-->
<!--                    :key="characterID"-->
<!--                    :card="character"-->
<!--                />-->
<!--            </div>-->
<!--        </title-section>-->

<!--        <title-section-->
<!--            v-if="story.elements.themes?.count"-->
<!--            class="!gap-6 bg-white px-4 py-6"-->
<!--        >-->
<!--            <template #title>-->
<!--                <title-with-link-->
<!--                    class="!p-0"-->
<!--                    title="Top Themes"-->
<!--                    title-class="text-lg text-black font-bold"-->
<!--                />-->
<!--            </template>-->

<!--            <div class="flex w-full flex-col gap-6">-->
<!--                <theme-card-->
<!--                    v-for="(theme, themeID) in story.elements.themes.elements"-->
<!--                    :key="themeID"-->
<!--                    :card="theme"-->
<!--                />-->
<!--            </div>-->
<!--        </title-section>-->

<!--        <title-section-->
<!--            v-if="story.elements.plots?.count"-->
<!--            class="!gap-6 bg-white px-4 py-6"-->
<!--        >-->
<!--            <template #title>-->
<!--                <title-with-link-->
<!--                    class="!p-0"-->
<!--                    title="Top Plots"-->
<!--                    title-class="text-lg text-black font-bold"-->
<!--                />-->
<!--            </template>-->

<!--            <div class="flex w-full flex-col gap-6">-->
<!--                <plot-card-->
<!--                    v-for="(plot, plotID) in story.elements."-->
<!--                    :key="plotID"-->
<!--                    :card="plot"-->
<!--                />-->
<!--            </div>-->
<!--        </title-section>-->

<!--        <title-section-->
<!--            v-if="story?.settingsList"-->
<!--            class="!gap-6 bg-white px-4 py-6"-->
<!--        >-->
<!--            <template #title>-->
<!--                <title-with-link-->
<!--                    class="!p-0"-->
<!--                    title="Top Plots"-->
<!--                    title-class="text-lg text-black font-bold"-->
<!--                />-->
<!--            </template>-->

<!--            <div class="flex w-full flex-col gap-6">-->
<!--                <setting-card-->
<!--                    v-for="(setting, settingID) in story.settingsList"-->
<!--                    :key="settingID"-->
<!--                    :card="setting"-->
<!--                />-->
<!--            </div>-->
<!--        </title-section>-->
    </div>
</template>

<script lang="ts" setup>
import { PropType } from 'vue'
import PlotCard from '@/components/cards/PlotCard.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'
import EpisodeCard from '@/components/cards/EpisodeCard.vue'
import SettingCard from '@/components/cards/SettingCard.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'

import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import { Story } from '@/types/story'
import { StoryElementKey } from '@/types/elements'
import ElementCard from '@/components/cards/ElementCard.vue'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>

<style scoped></style>
