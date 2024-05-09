<template>
  <div class="flex flex-col gap-8 w-full">
    <div class="flex flex-col gap-6 px-4">
      <h4
        v-if="!user.stories?.length"
        class="text-lg text-black font-bold"
      >
        Stories
      </h4>

      <div
        v-if="user.stories?.length"
        class="flex flex-col gap-4 w-full"
      >
        <StoryCard
          v-for="(story, storyID) in user.stories"
          :key="storyID"
          :story="story"
        />
      </div>

      <div
        v-else
        class="w-full flex flex-col items-center gap-2 mt-[20%]"
      >
        <div
          class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-gray-400"
        >
          <plus-icon class="text-white" />
        </div>
        <span class="text-sm text-gray-400 font-normal">
          Start building your first story
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import StoryCard from '@/components/cards/StoryCard.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'

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
