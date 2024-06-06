<template>
    <div class="flex flex-col gap-8 w-full">
        <div class="flex flex-col px-4 gap-4">
            <h5 class="font-bold text-lg text-zinc-800">Story goals</h5>

            <ul class="flex flex-col">
                <li
                    v-for="(goal, goalID) in story.goals"
                    :key="goalID"
                    class="font-normal text-lg text-zinc-800 flex items-center gap-2 pl-2"
                >
                    <point-icon />
                    {{ goal }}
                </li>
            </ul>
        </div>

        <div class="flex flex-col px-4 gap-4">
            <h5 class="font-bold text-lg text-zinc-800">Market Comps</h5>
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

        <div class="flex flex-col px-4 gap-4">
            <h5 class="font-bold text-lg text-zinc-800">
                Your progress so far
            </h5>

            <div class="flex flex-wrap gap-2">
                <progress-card
                    v-for="(item, itemID) in story.progress"
                    :key="itemID"
                    :item="item"
                />
            </div>
        </div>

        <div
            v-if="story?.achievements?.length"
            class="flex flex-col px-4 gap-4"
        >
            <title-with-link
                title="Achievements"
                title-class="font-bold text-lg text-zinc-800"
                class="!p-0"
            />
            <title-section title="In progress">
                <items-list
                    v-slot="{ item }"
                    :items="
                        story?.achievements.filter((item) => !item.progress)
                    "
                >
                    <achievement-card
                        :item="item"
                        class="h-full"
                    />
                </items-list>
            </title-section>

            <title-section title="Earned">
                <items-list
                    v-slot="{ item }"
                    :items="story?.achievements.filter((item) => item.progress)"
                    class="gap-8"
                >
                    <achievement-card
                        :item="item"
                        class="h-full"
                    />
                </items-list>
            </title-section>
        </div>

        <div class="flex flex-col px-4">
            <h5 class="font-bold text-lg text-zinc-800 mb-4">
                Target audience
            </h5>

            <target-audience-card
                v-for="(audience, audienceID) in story.target_audience"
                :key="audienceID"
                :id="audienceID + 1"
                :audience="audience"
                :class="[
                    audienceID !== 0
                        ? 'border-t ${border-zinc-300} pt-2'
                        : 'pb-2',
                ]"
            />
        </div>


        <div class="flex flex-col px-4 gap-4">
            <title-with-link
                title="Top characters"
                title-class="font-bold text-lg text-zinc-800"
                class="!p-0"
            />

            <div
                v-if="story?.characters"
                class="w-full flex flex-col gap-2"
            >
                <character-card
                    v-for="(character, characterID) in story.characters"
                    :key="characterID"
                    :card="character"
                />
            </div>
        </div>

        <div class="flex flex-col px-4 gap-4">
            <title-with-link
                title="Top impactful scenes"
                title-class="font-bold text-lg text-zinc-800"
                class="!p-0"
            />

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
import SceneCard from '@/components/cards/SceneCard.vue'
import ProgressCard from '@/components/cards/ProgressCard.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'
import AchievementCard from '@/components/cards/AchievementCard.vue'
import TargetAudienceCard from '@/components/TargetAudienceCard.vue'

import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import ImageComponent from '@/components/ImageComponent.vue'

import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    story: {
        type: Object,
        required: true,
    },
})

</script>

<style scoped></style>
