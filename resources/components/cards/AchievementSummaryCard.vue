<template>
    <div :class="cardClass">
        <h6 class="text-xs text-gray-500">{{ title }}</h6>
        <p :class="valueClass">{{ value }}</p>
    </div>
</template>

<script lang="ts" setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    cardClass: {
        type: String,
        default:
            'flex flex-col gap-2 p-2 rounded-lg bg-slate-200 w-full max-w-28',
    },
    valueClass: {
        type: String,
        default: 'text-sm text-black font-bold',
    },
})
</script>

<style scoped></style>
