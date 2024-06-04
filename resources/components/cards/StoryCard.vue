<template>
  <div
    class="flex flex-col gap-2.5 items-start w-full rounded-lg p-2.5 bg-slate-200"
  >
    <div class="flex gap-2">
      <image-component
        :src="story?.image?.path"
        alt="story"
        class="rounded-lg object-cover w-20 h-20"
      />

      <div class="flex flex-col w-full">
        <h4 class="text-neutral-700 text-base font-bold">
          {{ truncateTitle(story.title, 18) }}
        </h4>

        <p
          v-if="story?.type"
          class="text-sm text-neutral-700 font-normal mt-3.5"
        >
          {{ story.type }}
        </p>

        <div
          v-if="story?.genres?.length"
          class="flex gap-2 items-center max-w-full overflow-auto opacity-50 text-black"
        >
          <template
            v-for="(genre, genreID) in story.genres"
            :key="genreID"
          >
            <point-icon v-if="genreID !== 0" />

            <p class="text-sm font-normal">
              {{ genre }}
            </p>
          </template>
        </div>
      </div>
    </div>

    <p
      v-if="story?.description"
      class="text-neutral-700 text-sm font-normal"
    >
      {{ truncateTitle(story.description, 145) }}
    </p>
  </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'

import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
  story: {
    type: Object,
    required: true,
  },
})

function truncateTitle(title: string, maxLength: number): string {
  return title.length > maxLength
    ? title.substring(0, maxLength - 3) + '...'
    : title
}
</script>

<style scoped></style>
