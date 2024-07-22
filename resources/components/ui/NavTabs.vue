<template>
    <div class="w-full overflow-x-auto bg-white px-3 py-4">
        <div class="flex gap-2">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                :class="[
                    'rounded-lg px-3 py-2 text-base',
                    modelValue === tab.key
                        ? 'bg-stone-800 text-stone-50'
                        : 'bg-stone-100 text-stone-500',
                ]"
                @click="$emit('update:modelValue', tab.key)"
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
        required: true,
    },
    modelValue: {
        type: String,
        required: true,
    },
})

defineEmits(['update:modelValue'])
</script>
