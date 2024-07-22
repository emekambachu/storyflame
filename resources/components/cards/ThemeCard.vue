<template>
    <div
        class="flex flex-col gap-2 items-start w-full rounded-lg p-3 bg-stone-100"
    >
        <div class="flex items-center w-full justify-between">
            <div class="flex flex-col items-start gap-0.5">
                <span class="text-stone-500 text-[8px] font-bold uppercase">
                    {{ element?.type }}
                </span>
                <h6 class="font-bold text-sm text-stone-800">
                    {{ element?.title }}
                </h6>
            </div>

            <flame-icon :progress="element.progress" />
        </div>

        <p
            v-if="element.description"
            class="text-stone-600 text-sm font-normal"
        >
            {{ element.description }}
        </p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'

const props = defineProps({
    element: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
