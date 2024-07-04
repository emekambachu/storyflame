<template>
    <default-element-header
        :detail="story.logline"
        :genres="story.genres"
        :tags="['TV Show', 'MA-16']"
        :title="story.name"
        :background="story.image?.path"
    />
</template>
<script lang="ts" setup>
import { PropType } from 'vue'
import { Story } from '@/types/story'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'

const props = defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>
