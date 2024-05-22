<template>
    <div class="flex flex-col gap-8 w-full">
        <title-section title="Earned achievements">
            <items-list
                v-slot="{ item }"
                :items="achievements.filter((item) => item.progress)"
                class="gap-8"
            >
                <achievement-card
                    :item="item"
                    class="h-full"
                />
            </items-list>
        </title-section>

        <title-section title="In progress">
            <items-list
                v-slot="{ item }"
                :items="achievements.filter((item) => !item.progress)"
            >
                <achievement-card
                    :item="item"
                    class="h-full"
                />
            </items-list>
        </title-section>
    </div>
</template>

<script lang="ts" setup>
import ItemsList from '@/components/ItemsList.vue'
import TitleSection from '@/components/TitleSection.vue'
import AchievementCard from '@/components/cards/AchievementCard.vue'

const props = defineProps({
    achievements: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
