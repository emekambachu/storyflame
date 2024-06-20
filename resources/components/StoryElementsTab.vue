<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section
            v-if="story?.characters"
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
                    v-for="(character, characterID) in story?.characters"
                    :key="characterID"
                    :card="character"
                />
            </div>
        </title-section>

        <title-section
            v-if="story?.impactful_scenes"
            class="!gap-6 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Top impactful scenes"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <div class="flex w-full flex-col gap-6">
                <scene-card
                    v-for="(scene, sceneID) in story.impactful_scenes"
                    :key="sceneID"
                    :scene="scene"
                />
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import SceneCard from '@/components/cards/SceneCard.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'

import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import { Story } from '@/types/story'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>

<style scoped></style>
