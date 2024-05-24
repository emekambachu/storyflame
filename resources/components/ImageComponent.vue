<template>
  <img
    v-if="src"
    :src="src"
    alt="image"
  />
  <div
    v-else
    class="bg-slate-200 flex items-center justify-center"
  >
    <img
      src="@/assets/images/no_image.png"
      class="w-10 h-10"
    />
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  src: {
    type: String,
    default: null,
  },
})
</script>

<style scoped></style>
