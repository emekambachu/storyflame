<template>
    <div class="flex flex-col gap-1">
        <h5
            v-if="title"
            class="text-sm font-bold text-stone-500"
        >
            {{ title }}
        </h5>
        <p
            v-if="description"
            class="text-sm font-normal text-stone-800"
        >
            {{ description }}
        </p>
        <text-list
            v-if="list?.length"
            :items="list"
        />
    </div>
</template>

<script setup lang="ts">
import TextList from '@/components/TextList.vue'

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    list: {
        type: Array,
        default: () => [],
    },
})
</script>

<style scoped></style>
