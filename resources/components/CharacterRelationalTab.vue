<template>
    <div class="flex flex-col gap-2 bg-stone-100">
        <title-section
            title="Relationships in Episode"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <character-details-card
                v-for="(item, itemID) in data.relational.episode_relationships"
                :key="itemID"
                :data="item"
            />
        </title-section>

        <title-section
            title="Social Impact"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <div class="flex flex-col gap-4">
                <subtitle-section
                    v-for="(item, itemId) in data.relational.social_impact"
                    :key="itemId"
                    :title="item.title"
                    :description="item?.description"
                    :list="item?.list"
                    :class="{ 'border-t border-stone-200 pt-4': itemId !== 0 }"
                    description-class="text-stone-700 text-sm"
                    list-class="text-stone-700 text-sm"
                />
            </div>
        </title-section>

        <title-section
            title="Relationships throughout Series"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <character-details-card
                v-for="(item, itemID) in data.relational.series_relationships"
                :key="itemID"
                :data="item"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import MomentCard from '@/components/cards/MomentCard.vue'
import CharacterDetailsCard from '@/components/cards/CharacterDetailsCard.vue'

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
