<template>
    <div
        class="flex flex-col gap-2 items-start w-full rounded-lg p-3 bg-stone-100"
    >
        <div class="flex items-center w-full justify-between">
            <div class="flex flex-col items-start gap-0.5">
                <span class="text-stone-500 text-xs font-semibold">
                    {{ card?.type }}
                </span>
                <h6 class="font-bold text-sm text-stone-800">
                    {{ card?.title }}
                </h6>
            </div>

            <flame-icon :progress="card.progress" />
        </div>

        <p
            v-if="card.description"
            class="text-stone-600 text-sm font-normal"
        >
            {{ card.description }}
        </p>
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
