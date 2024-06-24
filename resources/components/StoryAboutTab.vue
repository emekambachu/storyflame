<template>
    <div class="flex w-full flex-col gap-8">
        <div class="flex flex-col gap-4 px-4">
            <h5 class="text-lg font-bold text-zinc-800">Story goals</h5>

            <ul class="flex flex-col">
                <li
                    v-for="(goal, goalID) in story?.goals"
                    :key="goalID"
                    class="flex items-center gap-2 pl-2 text-lg font-normal text-zinc-800"
                >
                    <point-icon />
                    {{ goal }}
                </li>
            </ul>
        </div>

        <div class="flex flex-col gap-4 px-4">
            <h5 class="text-lg font-bold text-zinc-800">Market Comps</h5>
            <div class="item-start flex gap-3">
                <image-component
                    v-for="(poster, posterID) in story?.market_comps"
                    :key="posterID"
                    :src="poster.image?.path"
                    alt="poster"
                    class="h-36 w-24 shrink-0 rounded-lg object-cover"
                />
            </div>
        </div>

        <div class="flex flex-col gap-4 px-4">
            <h5 class="text-lg font-bold text-zinc-800">
                Your progress so far
            </h5>

            <div class="flex flex-wrap gap-2">
                <progress-card
                    v-for="(item, itemID) in story?.progress"
                    :key="itemID"
                    :item="item"
                />
            </div>
        </div>

        <div
            v-if="story?.achievements?.length"
            class="flex flex-col gap-4 px-4"
        >
            <title-with-link
                class="!p-0"
                title="Achievements"
                title-class="font-bold text-lg text-zinc-800"
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
            <h5 class="mb-4 text-lg font-bold text-zinc-800">
                Target audience
            </h5>

            <div class="flex flex-col gap-3">
                <target-audience-card
                    v-for="(audience, audienceID) in story?.target_audience"
                    :key="audienceID"
                    :card="audience"
                />
            </div>
        </div>

        <div
            v-if="showDiscuss"
            class="fixed bottom-0 left-0 right-0 px-4 pb-3"
        >
            <discuss-component @close="showDiscuss = false" />
        </div>

        <div class="flex flex-col gap-4 px-4">
            <title-with-link
                class="!p-0"
                title="Top characters"
                title-class="font-bold text-lg text-zinc-800"
                @see-all="
                    router.push({
                        name: 'characters',
                    })
                "
            />

            <div
                v-if="story?.characters"
                class="flex w-full flex-col gap-2"
            >
                <character-card
                    @click="router.push({
                        name: 'character',
                        params: { character: characterID },
                    })"
                    v-for="(character, characterID) in story?.characters"
                    :key="characterID"
                    :card="character"
                />
            </div>
        </div>

        <div class="flex flex-col gap-4 px-4">
            <title-with-link
                class="!p-0"
                title="Top impactful scenes"
                title-class="font-bold text-lg text-zinc-800"
            />

            <div class="flex w-full flex-col gap-6">
                <scene-card
                    v-for="(scene, sceneID) in story?.impactful_scenes"
                    :key="sceneID"
                    :scene="scene"
                />
            </div>
        </div>

        <div
            v-if="showDiscuss"
            class="sticky bottom-0 left-0 right-0 px-4 pb-3"
        >
            <discuss-component @close="showDiscuss = false" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { PropType, ref } from 'vue'
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
import { Story } from '@/types/story'
import DiscussComponent from '@/components/DiscussComponent.vue'
import { useRouter } from 'vue-router'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})

const router = useRouter()

const showDiscuss = ref(true)
</script>

<style scoped></style>
