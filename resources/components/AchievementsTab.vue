<template>
  <div class="flex flex-col gap-8">
    <div class="flex flex-col gap-3">
      <h4 class="text-sm text-neutral-700 font-bold">Earned achievements</h4>

      <div class="flex gap-8 items-start max-w-full overflow-auto">
        <AchievementCard
          v-for="(achievement, achievementID) in achievements.earned"
          :key="achievementID"
          :item="achievement"
        />
      </div>
    </div>

    <div class="flex flex-col gap-3">
      <h4 class="text-sm text-neutral-700 font-bold">In progress</h4>
      <div class="flex gap-8 items-start max-w-full overflow-auto">
        <AchievementCard
          v-for="(achievement, achievementID) in achievements.in_progress"
          :key="achievementID"
          :item="achievement"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import AchievementCard from '@/components/AchievementCard.vue'

const props = defineProps({
  achievements: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped></style>
