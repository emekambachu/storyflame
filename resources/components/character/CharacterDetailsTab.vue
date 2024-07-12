<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 pt-2">
        <div
            v-if="data.images?.length"
            class="flex items-center gap-2 bg-white px-3 py-4"
        >
            <image-component
                v-for="(image, imageID) in data.images"
                :key="imageID"
                :src="image.path"
                class="h-20 w-20 shrink-0 rounded-xl"
            />
        </div>

        <title-section
            v-if="data?.goals"
            class="!gap-3 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Goals"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <text-list
                :items="data.goals"
                class="!text-stone-700"
            />
        </title-section>

        <title-section
            v-if="data?.character_details?.length"
            class="!gap-3 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Details"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <character-details-card
                v-for="(detail, detailID) in data.character_details"
                :key="detailID"
                :data="detail"
                :class="
                    detailID !== 0
                        ? 'border-t border-solid border-stone-200 pt-4'
                        : ''
                "
            />
        </title-section>

        <title-section class="!gap-5 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    title="Personality"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <div class="flex w-full items-center gap-3">
                <div class="w-full rounded-xl bg-stone-100 px-2.5 py-3">
                    <text-list
                        :items="data.personality.strong_sides"
                        point-class="mt-1.5"
                        class="!text-xs"
                    />
                </div>
                <div class="w-full rounded-xl bg-stone-100 px-2.5 py-3">
                    <text-list
                        :items="data.personality.week_sides"
                        point-class="mt-1.5"
                        class="!text-xs"
                    />
                </div>
            </div>
            <subtitle-section
                v-for="(item, itemID) in data.personality.list"
                :key="itemID"
                :title="item?.title"
                :description="item?.description"
                :list="item?.list"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'

import ImageComponent from '@/components/ImageComponent.vue'
import TextList from '@/components/TextList.vue'

import CharacterDetailsCard from '@/components/cards/CharacterDetailsCard.vue'
import SubtitleSection from '@/components/cards/SubtitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
