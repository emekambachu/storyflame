<template>
    <div class="flex flex-col gap-2 bg-stone-100">
        <title-section
            title="Goals"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <subtitle-section
                    v-for="(goal, goalId) in data.details_goals"
                    :key="goalId"
                    :title="goal.title"
                    :description="goal.description"
                />
            </div>
        </title-section>

        <title-section
            title="Obstacles & Conflict"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <subtitle-section
                    v-for="(item, itemId) in data.details_obtacles"
                    :key="itemId"
                    :title="item.title"
                    :description="item.description"
                />
            </div>
        </title-section>

        <title-section
            title="Major Choices"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <moment-card
                    v-for="(item, itemID) in data.moments"
                    :key="itemID"
                    :card="item"
                />
            </div>
        </title-section>

        <title-section
            title="Favorite Moments"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <moment-card
                    v-for="(item, itemID) in data.moments"
                    :key="itemID"
                    :card="item"
                />
            </div>
        </title-section>

        <title-section
            title="Plot Impact"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section :description="data.details_plot" />
        </title-section>

        <title-section
            title="Character Impact"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <subtitle-section
                    v-for="(item, itemId) in data.details_characters"
                    :key="itemId"
                    :title="item.title"
                    :description="item.description"
                />
            </div>
        </title-section>

        <title-section
            title="The Audience Learns"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <subtitle-section
                    v-for="(item, itemId) in data.details_audience_learns"
                    :key="itemId"
                    :title="item.title"
                    :description="item.description"
                />
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import MomentCard from '@/components/cards/MomentCard.vue'

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
