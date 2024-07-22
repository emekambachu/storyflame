<template>
    <section class="flex flex-col gap-3 text-left items-start w-full">
        <slot name="title">
            <h4
                v-if="title"
                :class="titleClasses"
            >
                {{ title }}
            </h4>
        </slot>
        <slot />
    </section>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: () => undefined,
    },
    titleClass: {
        type: String,
        default: ()=>undefined,
    },
    variant: {
        type: String,
        default: 'default',
    }
})

const titleClasses = computed(() => {
    if (props.titleClass) {
        return props.titleClass
    }

    switch (props.variant) {
        case 'muted':
            return 'text-sm font-semibold text-neutral-300'
        default:
            return 'text-sm text-neutral-700 font-bold px-4'
    }
})
</script>
