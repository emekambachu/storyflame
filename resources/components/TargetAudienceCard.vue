<template>
    <div class="flex flex-col gap-2 text-left">
        <h6 class="text-sm text-slate-500 font-bold">
            <span v-if="id !== 0">{{ id }}.</span>
            {{ audience.title }}
        </h6>
        <p class="text-sm text-zinc-800 font-normal opacity-60 -mt-1">
            {{ audience.description }}
        </p>

        <p class="flex items-start gap-2 text-sm text-black font-normal">
            <thumbs-icon class="text-lime-400 w-5 h-5 shrink-0" />
            {{ audience.prefer }}
        </p>
        <p class="flex items-start gap-2 text-sm text-black font-normal">
            <thumbs-icon class="text-red-600 rotate-180 w-5 h-5 shrink-0" />
            {{ audience.dislike }}
        </p>
    </div>
</template>

<script setup lang="ts">
import ThumbsIcon from '@/components/icons/ThumbsIcon.vue'
const props = defineProps({
    audience: {
        type: Object,
        required: true,
    },
    id: {
        type: Number,
        default: 0,
    },
})
</script>

<style scoped></style>
