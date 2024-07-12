<template>
    <default-element-header
        v-if="story"
        :detail="story.description"
        :genres="story.genres"
        :tags="['TV Show', 'MA-16']"
        :achievements="story?.achievements"
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
        type: Object,
        // type: Object as PropType<Story>,
        required: true,
    },
})
</script>
