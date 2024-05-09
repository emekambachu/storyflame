<template>
    <div
        class="flex flex-col gap-2 items-start w-full rounded-lg p-3 bg-zinc-200"
    >
        <div class="font-bold text-[10px] text-red-600 flex items-center gap-1">
            <point-icon />
            {{ scene.type }}
        </div>
        <div class="flex items-center justify-between w-full">
            <h6 class="font-bold text-sm text-neutral-700">
                {{ scene.name }}
            </h6>
            <span class="font-normal text-xs text-neutral-700">
                [#{{ scene.id }}]
            </span>
        </div>

        <p
            v-if="scene.description"
            class="text-neutral-700 text-sm font-normal"
        >
            {{ scene.description }}
        </p>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    scene: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
