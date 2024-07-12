<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 pt-2">
        <title-section
            v-if="data.arcs.episod_arc"
            class="!gap-3 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Episode Arc"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.arcs.episod_arc"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-500 font-bold"
            />
        </title-section>

        <title-section
            v-if="data?.arcs?.episod_impact"
            class="!gap-3 bg-white px-4 py-6"
        >
            <template #title>
                <title-with-link
                    title="Episode Impact"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.arcs.episod_impact"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-500 font-bold"
            />
        </title-section>

        <title-section class="!gap-5 bg-white px-4 py-6">
            <template #title>
                <title-with-link
                    title="Episode Milestones"
                    class="!p-0"
                    title-class="text-lg text-black font-bold"
                />
            </template>
            <div class="flex flex-col gap-3">
                <moment-card
                    v-for="(item, itemID) in data.arcs.moments"
                    :key="itemID"
                    :card="item"
                    class="rounded-xl bg-stone-100 p-4"
                />
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import MomentCard from '@/components/cards/MomentCard.vue'

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
