<template>
    <div class="w-full bg-white px-4 pb-6">
        <div
            class="flex w-full flex-col gap-6 rounded-lg border border-gray-100 bg-slate-50 pb-5"
        >
            <statistic-card />

            <h3 class="px-2 text-base font-semibold text-black">
                What we learned about you
            </h3>
            <title-section
                v-for="(item, itemID) in statistic"
                :key="itemID"
                class="!gap-2 !px-2"
            >
                <template #title>
                    <div class="flex flex-col">
                        <h4 class="text-sm font-semibold text-slate-400">
                            {{ item.title }}
                        </h4>
                    </div>
                </template>

                <p
                    v-if="item?.description"
                    class="text-sm font-normal text-neutral-950"
                >
                    {{ item.description }}
                </p>
                <div
                    class="flex w-full max-w-full items-start gap-3 overflow-auto"
                >
                    <image-component
                        v-for="(movie, movieID) in item.inspiration"
                        :key="movieID"
                        :src="movie?.poster"
                        class="h-36 w-24 shrink-0 rounded-lg object-cover"
                    />
                </div>
            </title-section>
        </div>
    </div>
</template>

<script setup lang="ts">
import ImageComponent from '@/components/ImageComponent.vue'
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
