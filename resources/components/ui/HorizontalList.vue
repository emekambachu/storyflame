<template>
    <div class="w-full">
        <div
            class="flex w-full max-w-full scroll-px-4 gap-4 overflow-scroll px-4"
        >
            <slot
                v-for="item in items"
                :key="item"
                :item="item"
                name="item"
            >
                {{ item }}
            </slot>
            <slot
                v-if="$slots.new"
                name="new"
            >
                here
            </slot>
        </div>
    </div>
</template>

<script generic="T" lang="ts" setup>
import { PropType } from 'vue'

defineSlots<{
    item(props: { item: T }): any
    new: any
}>()

defineProps({
    items: {
        type: Array as PropType<T[]>,
        default: () => [],
    },
})
</script>

<style scoped></style>
