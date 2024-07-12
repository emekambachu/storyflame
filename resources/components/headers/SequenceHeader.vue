<template>
    <default-element-header
        :title="sequence.name"
        :detail="sequence.description"
        :genres="sequence.genres"
        :achievements="sequence?.achievements"
        :progress="sequence?.progress"
        :tags="['TV Show', 'MA-16']"
        background="https://picsum.photos/1920/1080"
        class="pb-5 pt-20"
    />
</template>

<script lang="ts" setup>
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'

defineProps({
    sequence: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
