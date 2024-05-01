<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col gap-6">
      <div class="flex items-center justify-between">
        <h4 class="text-lg text-black font-bold">
          Finished
          <span class="opacity-50">
            ({{ user?.stories?.finished?.length }})
          </span>
        </h4>

        <button class="text-sm text-orange-500 font-normal">See All</button>
      </div>

      <div class="flex gap-4 items-center max-w-full overflow-auto">
        <div
          v-for="(story, storyID) in user.stories.finished"
          :key="storyID"
          class="flex flex-col gap-2 shrink-0"
        >
          <image-component
            :src="story.image?.path"
            alt="story"
            class="rounded-lg object-cover w-[133px] h-[125px]"
          />
          <p class="text-black text-sm font-semibold">
            {{ truncateTitle(story.title, 18) }}
          </p>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-6">
      <h4 class="text-lg text-black font-bold">
        In progress
        <span class="opacity-50">
          ({{ user?.stories?.in_progress?.length }})
        </span>
      </h4>

      <div class="flex flex-col gap-4 w-full">
        <div
          v-for="(story, storyID) in user.stories.in_progress"
          :key="storyID"
          class="flex gap-3 items-center w-full"
        >
          <image-component
            :src="story?.image?.path"
            alt="story"
            class="rounded-lg object-cover w-20 h-20"
          />

          <div class="flex flex-col justify-between gap-6 w-full">
            <h4 class="text-black text-sm font-semibold">
              {{ truncateTitle(story.title, 18) }}
            </h4>

            <div class="flex flex-col gap-2">
              <span class="text-black text-xs font-normal opacity-50">
                {{ story?.percent }}% complete
              </span>
              <progress-bar
                :percent="story.percent"
                class="w-full"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ProgressBar from '@/components/ProgressBar.vue'
import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
  user: {
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
