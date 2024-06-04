<template>
    <div :class="cardClass">
        <h6 class="text-xs text-gray-500">{{ item.title }}</h6>
        <p :class="valueClass">{{ item.value }}</p>
    </div>
</template>

<script setup lang="ts">
const props = defineProps({
    item: {
        type: Object,
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
