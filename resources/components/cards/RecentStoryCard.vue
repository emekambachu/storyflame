<template>
    <div class="flex flex-col gap-1 shrink-0">
        <div class="w-fit h-fit relative">
            <image-component
                :src="story.poster.path"
                alt="poster"
                class="rounded-lg object-cover w-24 h-24 shrink-0"
            />
            <flame-icon class="absolute right-1 bottom-1" />
        </div>
        <h4
            v-if="story.title"
            class="text-xs font-fjalla text-stone-950"
        >
            {{ story.title }}
        </h4>
        <p class="text-[8px] text-stone-800 font-inter">{{ story.episode }}</p>
    </div>
</template>

<script lang="ts" setup>
import FlameIcon from '@/components/icons/FlameIcon.vue'
import ImageComponent from '@/components/ImageComponent.vue'

defineProps({
    story: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
