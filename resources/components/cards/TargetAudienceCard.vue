<template>
    <div class="flex flex-col text-left bg-stone-100 p-4 rounded-lg">
        <h6 class="text-sm text-stone-800 font-bold">
            {{ card.title }}
        </h6>
        <p class="text-sm text-stone-500 font-normal opacity-60 mt-1">
            {{ card?.description }}
        </p>

        <p class="flex items-start gap-2 text-sm text-stone-800 font-normal mt-2">
           <div 
           class="bg-white rounded-full flex items-center justify-center w-6 h-6 shrink-0"
           > 
                <thumbs-icon class="text-blue-500 w-5 h-5 shrink-0" />
            </div>
            {{ card?.prefer }}
        </p>
        <p class="flex items-start gap-2 text-sm text-stone-800 font-normal mt-2">
           <div 
           class="bg-white rounded-full flex items-center justify-center w-6 h-6 shrink-0"
           > 
                <thumbs-icon class="text-red-600 rotate-180 w-5 h-5 shrink-0" />
            </div>
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
})
</script>

<style scoped></style>
