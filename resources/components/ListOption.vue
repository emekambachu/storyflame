<template>
  <div
    class="px-6 py-5 rounded-full flex items-center gap-2 outline outline-neutral-200"
    :class="[
      textClass,
      status ? 'bg-red-200 text-red-600' : 'bg-white text-neutral-700',
    ]"
  >
    <checkbox-item
      v-if="multiple"
      :status="status"
    />
    <slot name="text" />
  </div>
</template>

<script setup lang="ts">
import CheckboxItem from '@/components/CheckboxItem.vue'

// examplte of usage:
//          <ListOption
//             class="w-full"
//             @click="checkboxStatus = !checkboxStatus"
//             :status="checkboxStatus"
//           >
//             <template #text>Get feedback on a script</template>
//           </ListOption>

const props = defineProps({
  textClass: {
    type: String,
    default: 'font-normal text-xl',
  },
  status: {
    type: Boolean,
    default: false,
  },
  multiple: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped></style>
