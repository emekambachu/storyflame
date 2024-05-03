<template>
  <modal-popup @close="emit('close')">
    <template #content>
      <div class="w-full h-fit flex flex-col items-center">
        <img
          src="../assets/images/sad_smile.png"
          class="mb-6"
        />
        <span class="text-neutral-700 text-2xl font-bold mb-1">Oops</span>
        <p class="text-neutral-700 text-base font-bold">
          Sorry, we couldn’t understand your answer, could you please repeat it?
        </p>

        <button
          class="mt-8 rounded-full bg-red-600 px-8 py-3 text-pure-white text-sm font-normal"
        >
          Repeat my answer
        </button>

        <button class="mt-4 text-stone-600 text-sm font-normal">
          Write it with text
        </button>
      </div>
    </template>
  </modal-popup>
</template>

<script setup lang="ts">
import ModalPopup from '@/components/ModalPopup.vue'

const emit = defineEmits(['close'])
</script>

<style scoped></style>
