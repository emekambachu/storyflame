<template>
  <div
    class="z-30 fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center pt-4"
  >
    <div
      class="rounded-t-xl bg-pure-white px-6 pb-6 relative w-full flex flex-col gap-6"
    >
      <button
        class="my-3 w-12 h-[5px] rounded-full bg-slate-300 mx-auto"
        @click="emitClose"
      />
      <slot name="content" />
    </div>
  </div>
</template>

<script setup lang="ts">
const emit = defineEmits(['close'])

function emitClose() {
  emit('close')
}
</script>

<style scoped></style>
