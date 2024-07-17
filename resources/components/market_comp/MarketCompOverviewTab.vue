<template>
    <div class="flex flex-col gap-2 bg-stone-100 pt-2">
        <title-section
            title="Favorite Aspects"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.favorite_aspects"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>
        <title-section
            title="Top Similarities"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.top_similarities"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>
        <title-section
            title="Top Differences"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold "
        >
            <subtitle-section
                v-for="(item, itemId) in data.top_differences"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                description-class="text-stone-700 text-sm"
                list-class="text-stone-700 text-sm"
            />
        </title-section>

        <title-section
            title="Key Moments"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold mb-2"
        >
            <moment-card
                v-for="(item, itemID) in data.key_moments"
                :key="itemID"
                :card="item"
                class="rounded-xl bg-stone-50 p-4"
            />
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
