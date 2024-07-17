<template>
    <default-element-header
        :tags="data?.tags"
        :genres="data?.genres"
        :background="data.image?.path"
        :achievements="data?.achievements"
    >
        <template #title>
            <h1 class="line-clamp-1 font-inter text-xl">{{ data.title }}</h1>
        </template>
        <template #detail>
            <h1 class="line-clamp-3 font-inter text-sm text-stone-300">
                {{ data.description }}
            </h1>
        </template>
    </default-element-header>
</template>

<script lang="ts" setup>
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
// import { PropType } from 'vue'
// import { Theme } from '@/types/theme'

defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
