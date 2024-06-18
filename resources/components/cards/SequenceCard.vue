<template>
    <div
        class="flex flex-col gap-2 items-start w-full rounded-lg p-3 bg-slate-200"
    >
        <div class="flex items-center w-full justify-between">
            <div class="flex items-center gap-2 w-full">
                <h6 class="font-bold text-sm text-neutral-700">
                    {{ card.title }}
                </h6>
            </div>

            <flame-icon :priority="card.priority" />
        </div>

        <p
            v-if="card.description"
            class="text-neutral-700 text-sm font-normal"
        >
            {{ card.description }}
        </p>
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
