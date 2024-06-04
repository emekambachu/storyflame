<template>
    <div
        class="flex flex-col gap-2 items-start w-full rounded-lg p-3 bg-slate-200"
    >
        <div class="flex items-center justify-between w-full">
            <h6 class="font-bold text-sm text-neutral-700">
                {{ card.name }}
            </h6>
            <span class="font-normal text-sm text-neutral-700">
                {{ card.role }}
            </span>
        </div>

        <div
            v-if="card?.types?.length"
            class="flex items-center gap-2"
        >
            <p
                v-for="(type, typeID) in card.types"
                :key="typeID"
                class="font-normal text-sm text-neutral-400 flex gap-2 items-center"
            >
                <span v-if="typeID !== 0"><point-icon /></span>
                {{ type }}
            </p>
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
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
