<template>
    <title-section>
        <template #title>
            <h4
                v-if="title"
                class="text-lg text-black font-bold"
            >
                {{ title }}
            </h4>
            <div class="flex flex-col w-full">
                <call-to-action-card
                    class="w-full"
                    v-for="(item, itemID) in items"
                    :key="itemID"
                    :item="item"
                />
            </div>
        </template>
    </title-section>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import CallToActionCard from '@/components/cards/CallToActionCard.vue'

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    items: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
