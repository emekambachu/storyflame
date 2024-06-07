<template>
    <div class="relative flex shrink-0">
        <img
            v-if="icon"
            :src="icon"
            class="h-[72px] w-[64px] shrink-0"
        />

        <span
            v-if="date"
            class="absolute -bottom-4 left-0 right-0 mx-auto w-fit rounded-full bg-white px-1.5 py-0.5 text-[10px] font-semibold"
            :style="`color: ${color}`"
        >
            {{ date }}
        </span>
    </div>
</template>

<script setup lang="ts">
const props = defineProps({
    icon: {
        type: String,
        default: null,
    },
    percent: {
        type: Number,
        required: true,
    },
    color: {
        type: String,
        required: true,
    },
    date: {
        type: String,
        default: null,
    },
})
</script>

<style scoped></style>
