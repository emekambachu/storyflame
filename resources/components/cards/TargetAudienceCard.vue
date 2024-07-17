<template>
    <div class="flex flex-col rounded-lg bg-stone-100 p-4 text-left">
        <h6 :class="titleClass">
            {{ card.title }}
        </h6>
        <p
            v-if="card?.description"
            class="mt-1 text-sm font-normal text-stone-500 opacity-60"
        >
            {{ card?.description }}
        </p>

        <p
            class="mt-2 flex items-start gap-2 text-sm font-normal text-stone-800"
        >
            <thumbs-icon
                class="h-6 w-6 shrink-0 rounded-full bg-white p-0.5 text-blue-500"
            />

            {{ card?.prefer }}
        </p>
        <p
            class="mt-2 flex items-start gap-2 text-sm font-normal text-stone-800"
        >
            <thumbs-icon
                class="h-6 min-w-6 shrink-0 rotate-180 rounded-full bg-white p-0.5 text-red-600"
            />

            {{ card?.dislike }}
        </p>
    </div>
</template>

<script setup lang="ts">
import ThumbsIcon from '@/components/icons/ThumbsIcon.vue'
const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    titleClass: {
        type: String,
        default: 'text-sm font-bold text-stone-800',
    },
})
</script>

<style scoped></style>
