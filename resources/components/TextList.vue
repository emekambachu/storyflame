<template>
    <ul class="flex flex-col gap-1">
        <li
            v-for="(item, itemID) in items"
            :key="itemID"
            class="text-slate-600 flex items-start gap-2"
        >
            <point-icon class="mt-2" />
            <p
                v-if="item.title || item.description"
                class="text-sm text-slate-600 font-normal"
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
})
</script>

<style scoped></style>
