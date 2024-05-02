<template>
  <div
    class="z-30 fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
  >
    <div class="rounded-xl bg-pure-white p-6 relative max-w-lg w-full">
      <xmark-icon
        class="text-black absolute top-4 right-4 cursor-pointer"
        @click="emitClose"
      />
      <slot name="content" />
    </div>
  </div>
</template>

<script setup lang="ts">
import XmarkIcon from '@/components/icons/XmarkIcon.vue'

const emit = defineEmits(['close'])

function emitClose() {
  emit('close')
}
</script>

<style scoped></style>
