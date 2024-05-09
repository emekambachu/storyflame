<template>
    <ul
        :class="[
            direction === 'row'
                ? 'flex overflow-x-scroll items-stretch'
                : 'flex-col',
            {
                wrap: wrap,
            },
        ]"
        class="flex items-start w-full gap-3 px-4"
    >
        <li
            v-for="item in items"
            :key="item[keyBy]"
            class="shrink-0"
        >
            <slot :item="item" />
        </li>
    </ul>
</template>
<script lang="ts" setup>
defineProps({
    items: {
        type: Array,
        required: true,
    },
    keyBy: {
        type: String,
        default: 'id',
    },
    wrap: {
        type: Boolean,
        default: false,
    },
    direction: {
        type: String,
        default: 'row',
    },
})
</script>
