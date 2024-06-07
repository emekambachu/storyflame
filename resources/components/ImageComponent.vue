<template>
  <img
    v-if="src"
    :src="src"
    alt="image"
  />
  <div
    v-else
    class="bg-slate-300 flex items-center justify-center"
  >

    <no-image
      class="w-6 text-stone-400"
    />
  </div>
</template>

<script setup lang="ts">
import NoImage from '@/components/icons/NoImage.vue'

const props = defineProps({
  src: {
    type: String,
    default: null,
  },
})
</script>

<style scoped></style>
