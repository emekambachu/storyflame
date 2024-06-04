<template>
    <div class="flex flex-col gap-1 shrink-0">
        <image-component
            :src="media.poster"
            alt="poster"
            class="rounded-lg object-cover w-24 h-36 shrink-0"
        />
        <h4
            v-if="media.title"
            class="text-xs line-clamp-2"
        >
            {{ media.title }}
        </h4>
    </div>
</template>

<script lang="ts" setup>
import ImageComponent from '@/components/ImageComponent.vue'

defineProps({
    media: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
