<template>
    <div class="flex flex-col gap-2 rounded-lg bg-stone-100 p-4">
        <div class="flex w-full items-start justify-between">
            <div class="flex flex-col gap-0.5">
                <span
                    v-if="card?.story"
                    class="text-xs font-normal text-stone-500"
                >
                    {{ card?.story }}
                </span>

                <h5 class="text-sm font-bold text-stone-800">
                    {{ card.title }}
                </h5>

                <p
                    class="flex items-center gap-1 text-xs font-medium text-stone-500"
                >
                    Episode {{ card?.episode ?? 0 }}
                    <point-icon />
                    {{ card?.sequences ?? 0 }} Sequences
                </p>
            </div>

            <flame-icon
                :priority="card.readiness"
                class="!h-8 !w-8"
                flameClass="w-6 h-6"
            />
        </div>
        <hr class="w-full text-stone-500" />
        <p class="text-sm text-stone-600">{{ card.description }}</p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/icons/FlameIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
