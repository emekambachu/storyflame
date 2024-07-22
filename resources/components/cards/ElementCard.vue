<template>
    <component :is="component" :element="element" />
</template>

<script setup lang="ts">
import { computed, PropType } from 'vue'
import { ElementKey, ElementProgress } from '@/types/elements'
import CharacterCard from '@/components/cards/CharacterCard.vue'
import ThemeCard from '@/components/cards/ThemeCard.vue'
import SequenceCard from '@/components/cards/SequenceCard.vue'

const props = defineProps({
    type: {
        type: String as PropType<ElementKey>,
        required: true
    },
    element: {
        type: Object as PropType<ElementProgress>,
        required: true
    }
})

const component = computed(() => {
    switch (props.type) {
        case 'characters':
            return CharacterCard
        case 'themes':
            return ThemeCard
        case 'sequences':
            return SequenceCard
        default:
            throw new Error(`Unknown element key: ${props.type}`)
    }
})
</script>

<style scoped>

</style>
