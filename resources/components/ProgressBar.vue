<template>
  <div>
    <div class="bg-purple-50 rounded-lg w-full h-2 relative">
      <div
        :style="[`width: ${percent}%`]"
        class="absolute h-2 left-0 top-0 bg-red-600 rounded-lg"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  percent: {
    type: Number,
    default: 90,
  },
})
</script>

<style scoped></style>
