<template>
    <div
        class="px-6 py-5 rounded-full flex items-center gap-2"
        :class="[textClass, status ? selectedClass : unselectedClass]"
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

const props = defineProps({
    textClass: {
        type: String,
        default: 'font-normal text-xl',
    },
    status: {
        type: Boolean,
        default: false,
    },
    selectedClass: {
        type: String,
        default: 'bg-stone-100 text-stone-800 border border-stone-300',
    },
    unselectedClass: {
        type: String,
        default: 'bg-white text-stone-800 border border-stone-200',
    },
    multiple: {
        type: Boolean,
        default: false,
    },
})
</script>

<style scoped></style>
