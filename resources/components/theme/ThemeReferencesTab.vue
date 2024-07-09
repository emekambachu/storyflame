<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100 py-2">
        <title-section
            v-if="data.references?.historical"
            class="!gap-5 bg-white px-4 py-6"
        >
            <template #title>
                <h4 class="text-lg font-bold text-black">Historical</h4>
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.references.historical"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                :image="item?.image?.path"
                :lists="item?.lists"
                title-class="text-sm text-stone-900 font-bold"
            />
        </title-section>

        <title-section
            v-if="data.references?.narrative"
            class="!gap-5 bg-white px-4 py-6"
        >
            <template #title>
                <h4 class="text-lg font-bold text-black">Narrative</h4>
            </template>

            <subtitle-section
                v-for="(item, itemID) in data.references.narrative"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                :image="item?.image?.path"
                :lists="item?.lists"
                title-class="text-sm text-stone-900 font-bold"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import TitleSection from '@/components/TitleSection.vue'
import SubtitleSection from '@/components/cards/SubtitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
