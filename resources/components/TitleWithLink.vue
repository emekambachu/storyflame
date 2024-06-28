<template>
    <div class="flex w-full items-center justify-between px-4">
        <h4 :class="titleClass">{{ title }}</h4>

        <button
            @click="emit('see-all')"
            class="font-normal text-slate-400"
        >
            {{ buttonText }}
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
    titleClass: {
        type: String,
        default: 'text-sm text-neutral-700 font-bold',
    },
    buttonText: {
        type: String,
        default: 'See all',
    },
})
</script>
