<template>
    <div class="flex flex-col gap-8 w-full">
        <div class="flex flex-col px-4 gap-3">
            <h5 class="font-bold text-base text-zinc-800">Story goals</h5>

            <ul class="flex flex-col">
                <li
                    v-for="(goal, goalID) in story.goals"
                    :key="goalID"
                    class="font-normal text-base text-zinc-800 flex items-center gap-2 pl-2"
                >
                    <point-icon />
                    {{ goal }}
                </li>
            </ul>
        </div>

        <div class="flex flex-col px-4 gap-3">
            <h5 class="font-bold text-base text-zinc-800">Market Comps</h5>
            <div class="flex item-start gap-3">
                <image-component
                    v-for="(poster, posterID) in story.market_comps"
                    :key="posterID"
                    :src="poster.image?.path"
                    alt="poster"
                    class="rounded-lg object-cover w-24 h-36 shrink-0"
                />
            </div>
        </div>

        <div class="flex flex-col px-4 gap-3">
            <h5 class="font-bold text-base text-zinc-800">Target audience</h5>

            <p class="text-sm text-zinc-800 font-normal">
                Captivate its audience with its intricate political intrigue and
                complex characters, leading them through a labyrinth of
                unpredictable twists and emotional highs and lows. By immersing
                them in a richly detailed fantasy world, the story encourages
                viewers to reflect on themes of power, loyalty, and morality
                while questioning their own beliefs amidst shifting allegiances
                and ethical dilemmas.
            </p>
        </div>

        <div class="px-4">
            <discuss-component />
        </div>

        <div class="flex flex-col px-4 gap-3">
            <div class="w-full flex items-center justify-between">
                <h5 class="font-bold text-base text-zinc-800">
                    Top characters
                </h5>

                <button class="text-sm text-black opacity-50">See all</button>
            </div>

            <div
                v-if="story?.characters"
                class="w-full flex flex-col gap-2"
            >
                <character-card
                    v-for="(character, characterID) in story.characters"
                    :key="characterID"
                    :character="character"
                />
            </div>
        </div>

        <div class="flex flex-col px-4 gap-3">
            <div class="w-full flex items-center justify-between">
                <h5 class="font-bold text-base text-zinc-800">
                    Top impactful scenes
                </h5>

                <button class="text-sm text-black opacity-50">See all</button>
            </div>

            <div class="w-full flex flex-col gap-6">
                <scene-card
                    v-for="(scene, sceneID) in story.impactful_scenes"
                    :key="sceneID"
                    :scene="scene"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import SceneCard from '@/components/SceneCard.vue'
import CharacterCard from '@/components/CharacterCard.vue'
import ImageComponent from '@/components/ImageComponent.vue'
import DiscussComponent from '@/components/DiscussComponent.vue'

import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    story: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
