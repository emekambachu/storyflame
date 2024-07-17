<template>
    <default-element-header
        :detail="outline.description"
        :tags="outline.format"
        :genres="[outline?.story, outline?.type]"
        :achievements="outline?.achievements"
        :title="outline.title"
        :background="outline?.image?.path"
    />
</template>
<script lang="ts" setup>
import { PropType } from 'vue'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'

const props = defineProps({
    outline: {
        type: Object,
        required: true,
    },
})
</script>
