<template>
    <div class="flex flex-col rounded-lg bg-stone-100 p-4">
        <div class="flex w-full items-center justify-between">
            <div class="flex flex-col gap-0.5">
                <span class="text-[8px] font-bold uppercase text-stone-500">
                    {{ card?.subtitle }}
                </span>

                <h5 class="text-sm font-bold text-stone-800">
                    {{ card.title }}
                </h5>
            </div>

            <flame-icon
                :progress="card.progress"
                class="!h-8 !w-8"
                flameClass="w-6 h-6"
            />
        </div>
        <p class="mt-2 text-sm text-stone-600">{{ card.description }}</p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
