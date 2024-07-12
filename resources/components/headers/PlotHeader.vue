<template>
    <default-element-header :tags="plot?.tags" :achievements="plot?.achievements">
        <template #title>
            <h1 class="line-clamp-1 font-inter text-xl">{{ plot.title }}</h1>
        </template>
        <template #detail>
            <p class="line-clamp-4 font-inter text-sm text-stone-300">
                {{ plot.description }}
            </p>
        </template>
    </default-element-header>
</template>

<script lang="ts" setup>
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
// import { PropType } from 'vue'
// import { plot } from '@/types/plot'

defineProps({
    plot: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
