<template>
    <div class="px-4 pb-6 w-full bg-white">
        <div
            class="pb-5 w-full gap-6 rounded-lg bg-slate-50 border border-gray-100 flex flex-col"
        >
            <statistic-card />

            <h3 class="text-base text-black font-semibold px-2">
                What we learned about you
            </h3>
            <title-section
                v-for="(item, itemID) in statistic"
                :key="itemID"
                class="!gap-2 !px-2"
            >
                <template #title>
                    <div class="flex flex-col">
                        <h4 class="text-sm text-slate-400 font-semibold">
                            {{ item.title }}
                        </h4>
                    </div>
                </template>

                <p
                    v-if="item?.description"
                    class="text-sm text-neutral-950 font-normal"
                >
                    {{ item.description }}
                </p>
                <div
                    class="flex items-start gap-3 w-full max-w-full overflow-auto"
                >
                    <movie-card
                        v-for="(movie, movieID) in item.inspiration"
                        :key="movieID"
                        :media="movie"
                    />
                </div>
            </title-section>
        </div>
    </div>
</template>

<script setup lang="ts">
import MovieCard from '@/components/cards/MovieCard.vue'
import TitleSection from '@/components/TitleSection.vue'
import StatisticCard from '@/components/cards/StatisticCard.vue'

const props = defineProps({
    statistic: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
