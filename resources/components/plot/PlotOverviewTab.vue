<template>
    <div class="flex w-full flex-col gap-2 bg-slate-100">
        <title-section
            class="bg-white px-3 py-4"
            title="Main Protagonist"
            title-class="text-lg font-bold text-black"
        >
            <character-card
                v-if="data?.main_protagonist"
                :card="data.main_protagonist"
                :show-line="false"
            />

            <subtitle-section
                v-if="data?.goal"
                title="Goal:"
                title-class="text-stone-500 text-sm font-bold"
                :description="data.goal"
                description-class="text-slate-600 text-sm text-normal"
            />
        </title-section>

        <title-section
            class="bg-white px-3 py-4"
            title="Exploration"
            title-class="text-lg font-bold text-black"
        >
            <subtitle-section
                v-if="data?.exploration"
                :description="data.exploration"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import TitleSection from '@/components/TitleSection.vue'
import CharacterCard from '@/components/cards/CharacterCard.vue'
import SubtitleSection from '@/components/cards/SubtitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
