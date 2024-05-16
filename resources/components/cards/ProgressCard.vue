<template>
    <div
        class="w-full max-w-[109px] h-28 rounded-lg p-2 flex flex-col gap-2 items-center justify-center"
        :class="{
            'text-red-800': item.progress == 1,
            'text-amber-800': item.progress == 2,
            'text-yellow-800': item.progress == 3,
            'text-zinc-600': item.progress == 4,
        }"
        :style="
            item.progress == 1
                ? { background: 'rgba(254, 49, 36, 0.20)' }
                : item.progress == 2
                  ? { background: 'rgba(255, 146, 2, 0.20)' }
                  : item.progress == 3
                    ? { background: 'rgba(251, 196, 0, 0.20)' }
                    : item.progress == 4
                      ? { background: '#E6EAEF' }
                      : {}
        "
    >
        <h6 class="text-sm font-bold text-center">
            {{ item.name }}
        </h6>
        <flame-icon
            :priority="item.progress"
            class="!w-10 !h-10"
            flameClass="w-8 h-8"
        />
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/icons/FlameIcon.vue'

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
