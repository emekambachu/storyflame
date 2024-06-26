<template>
    <default-element-header
        :detail="story.description"
        :genres="story.genres"
        :tags="['TV Show', 'MA-16']"
        :title="story.name"
        background="https://picsum.photos/1920/1080"
    />
</template>
<script lang="ts" setup>
import { PropType } from 'vue'
import { Story } from '@/types/story'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'

defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>
