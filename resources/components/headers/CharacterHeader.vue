<template>
    <default-element-header :tags="['Protagonist', 'Main Character']">
        <template #title>
            <h1 class="line-clamp-1 font-inter text-xl">
                {{ character.name }}1
            </h1>
        </template>
        <template #detail>
            <p class="font-decorative text-sm italic text-stone-400">
                “{{ character.quote }}”
            </p>
        </template>
    </default-element-header>
</template>

<script lang="ts" setup>
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
import { PropType } from 'vue'
import { Character } from '@/types/character'

defineProps({
    character: {
        type: Object as PropType<Character>,
        required: true,
    },
})
</script>

<style scoped></style>
