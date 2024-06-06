<template>
    <div :class="card.prefer ? 'bg-blue-50' : 'bg-red-50'" class="flex items-center gap-2 rounded-full px-3 py-2">
        <thumbs-icon :class="card.prefer ? 'text-blue-500' : 'text-red-600 rotate-180'" class="w-[18px] h-[18px] shrink-0" />
        <p class="text-sm text-black font-normal">
            {{ card.description }}
        </p>
    </div>
</template>

<script setup lang="ts">
import ThumbsIcon from '@/components/icons/ThumbsIcon.vue'
const props = defineProps({
    card: {
        type: Object,
        required: true,
    }
})
</script>

<style scoped>

</style>