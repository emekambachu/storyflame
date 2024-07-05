<template>
    <div class="w-full">
        <div class="flex px-4 w-full max-w-full scroll-px-4 gap-4 overflow-scroll">
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

<script lang="ts" setup>
defineProps({
    items: {
        type: Array,
        default: () => [],
    },
})
</script>

<style scoped></style>
