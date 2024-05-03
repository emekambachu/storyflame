<template>
  <div
    class="flex flex-col items-center text-center w-full max-w-[90px] shrink-0"
  >
    <img
      v-if="item?.image?.path"
      :src="item.image.path"
      alt="icon"
      :class="{
        brightness: !item.achievement_date && item.percent !== 100,
      }"
      class="h-[89px] w-[82px] object-contain shrink-0"
    />

    <h6 class="text-black text-sm font-bold">{{ item.title }}</h6>
    <p
      v-if="item.achievement_date && item.percent == 100"
      class="text-black text-sm font-normal opacity-50"
    >
      {{ item.achievement_date }}
    </p>
    <div
      v-else
      class="w-full flex items-center gap-1 mt-1"
    >
      <progress-bar
        :percent="item.percent"
        class="w-full !h-[6px]"
      />
      <span class="text-[8px] text-gray-700 font-normal">
        {{ item.percent }}%
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import ProgressBar from '@/components/ProgressBar.vue'

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped lang="css">
.brightness {
  filter: brightness(0.8) contrast(0.3);
}
</style>
