<template>
  <div
    class="p-6 rounded-full flex items-center gap-2 outline outline-neutral-200 hover:outline-0 bg-white hover:bg-red-200"
    :class="textClass"
  >
    <slot name="text" />
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  textClass: {
    type: String,
    default: 'font-semibold text-xl text-neutral-700 hover:text-red-600',
  },
})
</script>

<style scoped></style>
