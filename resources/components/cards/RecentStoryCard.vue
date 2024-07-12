<template>
    <router-link
        :to="{
            name: 'story',
            params: { story: story?.id ?? 'test' },
        }"
        class="flex shrink-0 flex-col gap-1"
    >
        <div class="relative h-fit w-fit">
            <image-component
                :src="story.poster?.path"
                alt="poster"
                class="h-24 w-24 shrink-0 rounded-lg object-cover"
            />
            <flame-icon class="absolute bottom-1 right-1 bg-white" />
        </div>
        <h4
            v-if="story.title"
            class="font-fjalla text-xs text-stone-950"
        >
            {{ story.title }}
        </h4>
        <p class="font-inter text-[8px] text-stone-800">{{ story.episode }}</p>
    </router-link>
</template>

<script lang="ts" setup>
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import ImageComponent from '@/components/ImageComponent.vue'
import { PropType } from 'vue'
import { Story } from '@/types/story'

defineProps({
    story: {
        type: Object as PropType<Story>,
        required: true,
    },
})
</script>

<style scoped></style>
