<template>
    <div
        class="flex items-start justify-between gap-4 bg-stone-100 rounded-lg p-4"
    >
        <div class="flex flex-col gap-1">
            <p class="flex items-center gap-1.5 text-sm text-stone-500">
                {{ card.story }}
                <point-icon />
                {{ card.type }}
            </p>
            <h5 class="text-base text-stone-800 font-semibold">
                {{ card.title }}
            </h5>
            <p class="text-sm text-stone-700">
                {{ card.description }}
            </p>
        </div>

        <button
            class="bg-white rounded-full h-9 w-9 shrink-0 flex items-center justify-center"
        >
            <microphone-icon class="text-stone-400 w-4 h-4" />
        </button>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
