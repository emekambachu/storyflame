<template>
    <div
        class="flex h-14 w-full max-w-[110px] flex-col items-start justify-center gap-2 rounded-lg p-2"
        :style="backgroundColorStyle"
    >
        <h6
            class="text-center text-xs font-bold"
            :class="titleColorClass"
        >
            {{ item.name }}
        </h6>

        <div class="flex w-full items-center justify-between">
            <p
                class="text-sm font-semibold"
                :class="countColorClass"
            >
                {{ item?.count ?? 0 }}
            </p>
            <flame-icon
                :progress="item.progress"
                class="!h-5 !w-5 !bg-transparent"
                :class="flameColorClass"
                flameClass="w-5 h-5"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import FlameIcon from '@/components/icons/FlameIcon.vue'

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})

const titleColorClass = computed(() => {
    const progress = props.item.progress
    if (progress > 90) return 'text-red-600'
    if (progress > 60) return 'text-amber-500'
    if (progress > 30) return 'text-yellow-500'
    return 'text-slate-400'
})

const countColorClass = computed(() => {
    const progress = props.item.progress
    if (progress > 90) return 'text-red-400'
    if (progress > 60) return 'text-amber-400'
    if (progress > 30) return 'text-yellow-400'
    return 'text-slate-400'
})

const flameColorClass = computed(() => {
    const progress = props.item.progress
    if (progress > 90) return 'text-red-500'
    if (progress > 60) return 'text-amber-500'
    if (progress > 30) return 'text-yellow-500'
    return 'text-slate-400'
})

const backgroundColorStyle = computed(() => {
    const progress = props.item.progress
    if (progress > 90) return { background: 'rgba(254, 49, 36, 0.20)' }
    if (progress > 60) return { background: 'rgba(255, 146, 2, 0.20)' }
    if (progress > 30) return { background: 'rgba(251, 196, 0, 0.20)' }
    return { background: '#E6EAEF' }
})
</script>

<style scoped></style>
