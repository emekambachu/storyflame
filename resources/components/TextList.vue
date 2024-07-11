<template>
    <ul
        class="flex flex-col gap-1"
        :class="listClass"
    >
        <li
            v-for="(item, itemID) in items"
            :key="itemID"
            class="flex items-start gap-2"
        >
            <point-icon :class="pointClass" />
            <p
                v-if="item.title || item.description"
                class="font-normal"
            >
                <span class="font-bold">{{ item?.title }}</span>
                {{ item?.description }}
            </p>
            <p
                v-else
                class="text-sm text-slate-600 font-normal"
            >
                {{ item }}
            </p>
        </li>
    </ul>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    pointClass: {
        type: String,
        default: 'mt-2',
    },
    listClass: {
        type: String,
        defatul: 'text-slate-600 text-sm font-normal',
    },
})
</script>

<style scoped></style>
