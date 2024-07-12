<template>
    <div class="flex flex-col gap-2 bg-stone-100 pt-2">
        <title-section
            title="Archetypes"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.references.archetypes"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :quote="item?.quote"
                :list="item?.list"
                :lists="item?.lists"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>

        <title-section
            title="Historical Figures"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.references.historical_figures"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :quote="item?.quote"
                :list="item?.list"
                :lists="item?.lists"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>

        <title-section
            title="Fictional References"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.references.fictional_references"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :quote="item?.quote"
                :list="item?.list"
                :lists="item?.lists"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
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
