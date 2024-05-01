<template>
  <div>
    <div class="bg-purple-50 rounded-lg w-full h-2 relative">
      <div
        :style="[`width: ${percent}%`]"
        class="absolute w-0 h-2 left-0 top-0 bg-red-600 rounded-lg duration-300"
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
