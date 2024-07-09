<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section
            class="bg-white px-3 py-4"
            title="Story Points"
            title-class="text-lg font-bold text-black"
        >
            <subtitle-section
                v-for="(item, itemID) in data?.story_points"
                :key="itemID"
                :title="item?.title"
                title-class="text-stone-500 text-sm font-bold"
                :description="item.description"
                description-class="text-stone-800 text-sm text-normal"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
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
