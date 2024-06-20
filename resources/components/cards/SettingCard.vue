<template>
    <div class="flex flex-col gap-2 rounded-lg bg-stone-100 p-2">
        <div class="flex w-full items-start justify-between">
            <div class="flex flex-col gap-0.5">
                <h5 class="text-base font-bold text-stone-800">
                    {{ card.title }}
                </h5>

                <p
                    class="flex items-center gap-1 text-xs font-medium text-stone-500"
                >
                    {{ card?.episodes ?? 0 }} Episodes
                    <point-icon />
                    {{ card?.characters ?? 0 }} Characters
                </p>
            </div>

            <flame-icon
                :progress="card.progress"
                class="!h-8 !w-8"
                flameClass="w-6 h-6"
            />
        </div>
        <hr class="w-full text-stone-500" />
        <p class="text-sm text-stone-600">{{ card.description }}</p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
