<template>
    <div class="flex flex-col gap-8 w-full px-4">
        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">
                    Achievements Summary
                </h4>
            </template>
            <div class="flex items-center gap-3 justify-between w-full">
                <achievement-summary-card
                    v-for="(item, itemID) in data.achievements_summary"
                    :key="itemID"
                    :item="item"
                />
            </div>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Achievements</h4>
            </template>

            <div class="flex flex-col gap-6 w-full">
                <title-section>
                    <template #title>
                        <title-with-link
                            title="In progress"
                            class="!p-0"
                            title-class="text-base text-slate-500 font-bold p-0"
                        />
                    </template>
                    <items-list
                        class="!p-0"
                        v-slot="{ item }"
                        :items="
                            data.achievements.filter(
                                (item) =>
                                    item.progress < 100 && item.progress > 0
                            )
                        "
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>

                <title-section>
                    <template #title>
                        <title-with-link
                            title="To earn"
                            class="!p-0"
                            title-class="text-base text-slate-500 font-bold p-0"
                        />
                    </template>
                    <items-list
                        class="!p-0"
                        v-slot="{ item }"
                        :items="
                            data.achievements.filter((item) => !item.progress)
                        "
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>

                <title-section>
                    <template #title>
                        <title-with-link
                            title="Earned"
                            class="!p-0"
                            title-class="text-base text-slate-500 font-bold p-0"
                        />
                    </template>
                    <items-list
                        v-slot="{ item }"
                        :items="
                            data.achievements.filter(
                                (item) => item.progress == 100
                            )
                        "
                        class="gap-8 !p-0"
                    >
                        <achievement-card
                            :item="item"
                            class="h-full"
                        />
                    </items-list>
                </title-section>
            </div>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
import TitleWithLink from '@/components/TitleWithLink.vue'
import AchievementSummaryCard from '@/components/cards/AchievementSummaryCard.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
