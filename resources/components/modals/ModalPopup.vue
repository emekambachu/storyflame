<template>
    <div class="w-full max-w-md mx-auto p-1">
        <div
            ref="container"
            :style="{
                transform: offset ? `translateY(${offset}px)` : undefined,
            }"
            class="min-h-96 pb-10 bg-white rounded-[32px] w-full flex flex-col gap-6 shadow-2xl"
        >
            <button
                class="py-2 w-full flex justify-center"
                @touchend.passive="onDragEnd"
                @touchmove.passive="onDrag"
                @touchstart.passive="onDragStart"
            >
                <span class="bg-gray-300 rounded-full h-1.5 w-1/6"></span>
            </button>
            <div class="px-6 flex flex-col gap-10">
                <slot />
                <div class="flex flex-col">
                    <button
                        v-if="primary"
                        class="bg-red-600 rounded-3xl text-white py-3 font-medium text-base w-full"
                    >
                        {{ primary }}
                    </button>
                    <button
                        v-if="secondary"
                        class="text-gray-400/50 py-3 text-base font-medium w-full"
                    >
                        {{ secondary }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { animate } from 'motion'

const emit = defineEmits(['close'])

const props = defineProps({
    primary: {
        type: String,
        default: () => undefined,
    },
    secondary: {
        type: String,
        default: () => undefined,
    },
})

const offset = ref(0)
const touchStart = ref(0)
const container = ref<HTMLDivElement | null>(null)

function onDragStart(e: TouchEvent) {
    touchStart.value = e.touches[0].clientY
}

function onDrag(e: TouchEvent) {
    const touchEnd = e.touches[0].clientY
    const diff = touchEnd - touchStart.value
    // apply a function to the diff to make the modal move slower
    offset.value = Math.abs(diff) - Math.abs(diff) / 2
    offset.value *= diff > 0 ? 1 : -1
}

function onDragEnd(e: TouchEvent) {
    if (offset.value > 50) {
        emit('close')
    } else {
        if (!container.value) return
        animate(
            container.value,
            {
                transform: [`translateY(${offset.value}px)`, 'translateY(0px)'],
            },
            { duration: 0.3 }
        ).finished.then(() => {
            offset.value = 0
        })
    }
}
</script>

<style scoped></style>
