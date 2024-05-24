<template>
    <div
        class="flex flex-col gap-2 p-2 rounded-lg bg-slate-200 w-full max-w-28"
    >
        <h6 class="text-xs text-gray-500">{{ item.title }}</h6>
        <p class="text-sn text-black font-bold">{{ item.value }}</p>
    </div>
</template>

<script setup lang="ts">
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
