<template>
    <div class="flex flex-col bg-stone-100 rounded-lg p-4">
        <div class="w-full flex items-center justify-between">
            <div class="flex flex-col gap-0.5">
                <span class="text-xs text-stone-400 font-semibold">
                    {{ card.subtitle }}
                </span>

                <h5 class="text-sm text-stone-800 font-bold">
                    {{ card.title }}
                </h5>
            </div>

            <flame-icon
                :priority="card.readiness"
                class="!w-8 !h-8"
                flameClass="w-6 h-6"
            />
        </div>
        <p class="text-sm text-stone-600 mt-2">{{ card.description }}</p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/icons/FlameIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
