<template>
    <div class="flex items-center justify-between w-full px-4">
        <h4 :class="titleClass">{{ title }}</h4>

        <button @click="emit('see-all')">See all</button>
    </div>
</template>
<script lang="ts" setup>
const emit = defineEmits(['see-all'])

defineProps({
    title: {
        type: String,
        default: () => undefined,
    },
    titleClass: {
        type: String,
        default: 'text-sm text-neutral-700 font-bold',
    },
})
</script>
