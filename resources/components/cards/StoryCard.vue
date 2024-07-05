<template>
  <router-link
    :to="`/stories/${card.id}`"
    is="div"
    class="flex flex-col gap-3 items-start w-full rounded-lg p-2 bg-stone-100"
  >
    <div class="flex gap-2 w-full">
      <image-component
        :src="card?.image?.path"
        alt="story"
        class="rounded-lg object-cover w-[72px] h-[72px] shrink-0"
      />

      <div class="flex flex-col w-full gap-1 w-full">
        <div class="flex items-center justify-between gap-1 w-full">
        <h4 class="text-stone-950 text-base text-xl font-bold font-fjalla w-full">
          {{ truncateTitle(card.name, 23) }}
        </h4>

        <flame-icon :progress="card.progress" flame-class="w-6 h-6" />
      </div>

        <p
          v-if="card?.type"
          class="text-sm text-stone-700 font-normal"
        >
          {{ card.type }}
        </p>

        <div
          v-if="card?.genres?.length"
          class="flex gap-2 items-center max-w-full overflow-auto text-sm text-stone-500"
        >
          <template
            v-for="(genre, genreID) in card.genres"
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
      v-if="card?.description"
      class="text-stone-700 text-sm font-normal"
    >
      {{ truncateTitle(card.description, 145) }}
    </p>
  </router-link>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

import ImageComponent from '@/components/ImageComponent.vue'
import { PropType } from 'vue'
import { Story } from '@/types/story'

const props = defineProps({
  card: {
    type: Object as PropType<Story>,
    required: true,
  },
})

function truncateTitle(title: string | undefined, maxLength: number): string {
    if (!title) return ''
    return title.length > maxLength
        ? title.substring(0, maxLength - 3) + '...'
        : title
}
</script>

<style scoped></style>
