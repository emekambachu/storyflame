<template>
    <div class="flex flex-col gap-2 bg-stone-100 pt-2">
        <div class="flex items-center gap-1 bg-white p-3">
            <image-component
                v-for="(image, imageID) in data.arc_images"
                :key="imageID"
                :src="image.image"
                class="h-20 w-20 rounded"
            />
        </div>

        <title-section
            title="Summary"
            class="bg-white py-5"
        >
            <p
                v-for="(paragraph, paragraphID) in data.arc_paragraphs"
                :key="paragraphID"
                class="px-3 text-sm text-stone-700"
            >
                {{ paragraph }}
            </p>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
