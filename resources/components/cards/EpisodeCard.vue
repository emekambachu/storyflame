<template>
    <div class="flex flex-col gap-2 rounded-lg bg-stone-100 p-4">
        <div class="flex w-full items-start justify-between">
            <div class="flex flex-col gap-0.5">
                <p
                    v-if="card?.episode || card?.story"
                    class="flex items-center gap-1 text-[8px] font-bold uppercase text-stone-500"
                >
                    {{ card?.episode }}
                    <point-icon
                        v-if="card?.episode && card?.story"
                        class="h-1 w-1"
                    />
                    {{ card?.story }}
                </p>

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
        <template v-if="card?.description">
            <hr class="w-full text-stone-500" />
            <p class="text-sm text-stone-600">{{ card.description }}</p>
        </template>
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
