<template>
    <section class="flex flex-col gap-3 text-left items-start w-full">
        <slot name="title">
            <h4
                v-if="title"
                :class="titleClass"
            >
                {{ title }}
            </h4>
        </slot>
        <slot />
    </section>
</template>

<script lang="ts" setup>
defineProps({
    title: {
        type: String,
        default: () => undefined,
    },
    titleClass: {
        type: String,
        default: 'text-sm text-neutral-700 font-bold px-4',
    },
})
</script>
