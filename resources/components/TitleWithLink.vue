<template>
    <div class="flex items-center justify-between w-full px-4">
        <h4 class="text-sm text-neutral-700 font-bold">{{ title }}</h4>

        <button
            class="text-sm text-black font-normal opacity-50"
            @click="emit('see-all')"
        >
            See all
        </button>
    </div>
</template>
<script lang="ts" setup>
const emit = defineEmits(['see-all'])

defineProps({
    title: {
        type: String,
        default: () => undefined,
    },
})
</script>
