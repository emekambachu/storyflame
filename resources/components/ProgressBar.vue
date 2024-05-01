<template>
  <div class="bg-slate-200 rounded-lg w-full h-2 relative">
    <div
      :style="[`width: ${percent}%`]"
      class="absolute h-full left-0 top-0 bg-red-600 rounded-lg"
    />
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
