<template>
    <default-element-header
        :tags="['Major theme']"
        :genres="['Game of Thrones', 'Ep 101: The Call North']"
        :background="theme.image?.path"
        :achievements="theme?.achievements"
    >
        <template #title>
            <h1 class="line-clamp-1 font-inter text-xl">{{ theme.name }}</h1>
        </template>
        <template #detail>
            <h1 class="line-clamp-1 font-inter text-sm text-stone-300">
                {{ theme.name }}
            </h1>
        </template>
    </default-element-header>
</template>

<script lang="ts" setup>
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
// import { PropType } from 'vue'
// import { Theme } from '@/types/theme'

defineProps({
    theme: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
