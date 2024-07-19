<template>
    <div class="flex w-full flex-col gap-8">
        <title-section title="Earned achievements">
            <items-list
                v-slot="{ item }"
                :items="achievements.filter((item) => item.progress)"
                class="gap-8"
            >
                <achievement-in-progress-card
                    :card="item"
                    class="h-full"
                />
            </items-list>
        </title-section>

        <title-section title="In progress">
            <items-list
                v-slot="{ item }"
                :items="achievements.filter((item) => !item.progress)"
            >
                <achievement-in-progress-card
                    :card="item"
                    class="h-full"
                />
            </items-list>
        </title-section>
    </div>
</template>

<script lang="ts" setup>
import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
// import AchievementCompletedCard from '@/components/cards/AchievementCompletedCard.vue'
import AchievementInProgressCard from '@/components/cards/AchievementInProgressCard.vue'

const props = defineProps({
    achievements: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
