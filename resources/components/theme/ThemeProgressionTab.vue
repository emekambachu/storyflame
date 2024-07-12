<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 pt-2">
        <title-section
            v-if="data.progression?.character_embodiments"
            class="!gap-5 bg-white px-4 py-6"
        >
            <template #title>
                <h4 class="text-lg font-bold text-black">Episode Arc</h4>
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.progression.character_embodiments"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-600 font-bold"
                description-class="mt-1 text-sm font-normal text-zinc-800"
            />
        </title-section>

        <title-section
            v-if="data?.progression?.key_moments"
            class="!gap-3 bg-white px-4 py-6"
        >
            <h4 class="text-lg font-bold text-black">Key Moments</h4>

            <moment-card
                v-for="(item, itemID) in data.progression.key_moments"
                :key="itemID"
                :card="item"
                class="rounded-xl bg-stone-100 p-4"
            />
        </title-section>

        <title-section class="!gap-5 bg-white px-4 py-6">
            <template #title>
                <h4 class="text-lg font-bold text-black">
                    Thematic Progression & Conflict
                </h4>
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.progression
                    .thematic_progression_conflict"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-600 font-bold"
                description-class="mt-1 text-sm font-normal text-zinc-800"
            />
        </title-section>

        <title-section class="!gap-5 bg-white px-4 py-6">
            <template #title>
                <h4 class="text-lg font-bold text-black">Symbols & Motifs</h4>
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.progression.symbols_motifs"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-600 font-bold"
                description-class="mt-1 text-sm font-normal text-zinc-800"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import MomentCard from '@/components/cards/MomentCard.vue'

import SubtitleSection from '@/components/cards/SubtitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
