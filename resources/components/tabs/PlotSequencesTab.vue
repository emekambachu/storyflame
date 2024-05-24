<template>
    <title-section class="px-4">
        <template #title>
            <h4 class="text-lg text-black font-bold">Sequences</h4>
        </template>
        <div class="flex flex-col gap-2">
            <sequence-card
                v-for="(goal, goalID) in data.data.goals"
                :key="goalID"
                :card="goal"
            />
        </div>
    </title-section>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import SequenceCard from '@/components/cards/SequenceCard.vue'

const data = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
