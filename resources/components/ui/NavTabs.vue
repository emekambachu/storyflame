<template>
    <div class="w-full overflow-x-auto py-4 px-3 bg-white">
        <div class="flex gap-2">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="$emit('update:modelValue', tab.key)"
                :class="[
          'px-3 py-2 rounded-lg text-base',
          modelValue === tab.key
            ? 'bg-stone-800 text-stone-50'
            : 'bg-stone-100 text-stone-500'
        ]"
            >
                {{ tab.title }}
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
defineProps({
    tabs: {
        type: Array,
        required: true
    },
    modelValue: {
        type: String,
        required: true
    }
})

defineEmits(['update:modelValue'])
</script>
