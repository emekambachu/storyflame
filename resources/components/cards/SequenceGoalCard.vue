<template>
    <div class="w-full flex items-center justify-between">
        <p class="text-zinc-700 text-xs font-normal max-w-[90%]">
            {{ goal.description }}
        </p>
        <flame-icon :priority="goal.priority" />
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/icons/FlameIcon.vue'

const props = defineProps({
    goal: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
