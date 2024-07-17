<template>
    <default-element-header
        :detail="market_comp.description"
        :tags="market_comp.format"
        :genres="market_comp?.genres"
        :achievements="market_comp?.achievements"
        :background="market_comp?.image?.path"
    >
        <template #title>
            <h1 class="line-clamp-1 font-fjalla text-3xl">
                {{ market_comp.title }}
            </h1>
        </template>
        <template #detail>
            <h1 class="line-clamp-3 font-inter text-sm text-stone-300">
                {{ market_comp.description }}
            </h1>
        </template>
    </default-element-header>
</template>
<script lang="ts" setup>
import { PropType } from 'vue'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'

const props = defineProps({
    market_comp: {
        type: Object,
        required: true,
    },
})
</script>
