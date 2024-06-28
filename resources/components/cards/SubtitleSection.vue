<template>
    <div class="flex flex-col gap-1">
        <h6
            v-if="title"
            class="text-sm font-bold text-black"
        >
            {{ title }}
        </h6>
        <p
            v-if="description"
            class="text-sm font-normal text-stone-700"
        >
            {{ description }}
        </p>
    </div>
</template>

<script setup lang="ts">
const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
})
</script>

<style scoped></style>
