<template>
    <div class="flex flex-col gap-2 rounded-lg bg-stone-50 p-4">
        <div class="flex w-full items-center justify-between">
            <div class="flex w-full items-center gap-2">
                <span
                    class="flex h-full items-center justify-center rounded bg-stone-400 px-2 py-0.5 text-[10px] leading-[160%] text-white"
                >
                    {{ card.number > 9 ? '' : '0' + card.number }}
                </span>

                <div class="flex flex-col">
                    <span
                        v-if="card?.type"
                        class="text-[8px] font-bold uppercase text-stone-500"
                    >
                        {{ card.type }}
                    </span>
                    <h5 class="text-sm font-bold text-stone-800">
                        {{ card.title }}
                    </h5>
                </div>
            </div>

            <flame-icon
                :progress="card.progress"
                class="!h-8 !w-8"
                flameClass="w-6 h-6"
            />
        </div>
        <template v-if="expanded">
            <p
                v-if="card?.description"
                class="text-sm text-stone-700"
            >
                {{ card.description }}
            </p>
            <template v-if="card.paragraphs?.length">
                <p
                    v-for="(paragraph, paragraphID) in card.paragraphs"
                    :key="paragraphID"
                    class="text-sm text-stone-700"
                >
                    {{ paragraph }}
                </p>
            </template>
        </template>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
    expanded: {
        type: Boolean,
        default: true,
    },
})
</script>

<style scoped></style>
