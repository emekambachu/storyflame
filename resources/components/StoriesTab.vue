<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col gap-6">
      <h4 class="text-lg text-black font-bold">Stories</h4>

      <div class="flex flex-col gap-4 w-full">
        <StoryCard
          v-for="(story, storyID) in user.stories.in_progress"
          :key="storyID"
          :story="story"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import StoryCard from '@/components/StoryCard.vue'

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
