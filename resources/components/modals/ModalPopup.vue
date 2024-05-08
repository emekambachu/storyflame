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
                @touchstart.passive="onTouchDragStart"
                @touchend.passive="onDragEnd"
                @touchmove.passive="onTouchDrag"
                @mousedown.passive="onClickDragStart"
                @mouseup.passive="onDragEnd"
            >
                <span class="bg-gray-300 rounded-full h-1.5 w-1/6"></span>
            </button>
            <div class="px-6 flex flex-col gap-10 grow">
                <slot />
                <div class="flex flex-col mt-auto">
                    <button
                        v-if="primary"
                        class="bg-red-600 rounded-3xl text-white py-3 font-medium text-base w-full"
                        @click="emit('primary')"
                    >
                        {{ primary }}
                    </button>
                    <button
                        v-if="secondary"
                        class="text-gray-400/50 py-3 text-base font-medium w-full"
                        @click="emit('secondary')"
                    >
                        {{ secondary }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { inject, ref } from 'vue'
import { animate } from 'motion'

const emit = defineEmits(['close', 'primary', 'secondary'])

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

const close = inject('close-current-modal') as () => void

const offset = ref(0)
const touchStart = ref<number | undefined>(0)
const container = ref<HTMLDivElement | null>(null)

function onDragStart(y: number) {
    touchStart.value = y
}

function onTouchDragStart(e: TouchEvent) {
    onDragStart(e.touches[0].clientY)
}

function onClickDragStart(e: MouseEvent) {
    onDragStart(e.clientY)
    document.addEventListener('mousemove', onClickDrag)
}

function onDrag(y: number) {
    if (touchStart.value === undefined) return
    const diff = y - touchStart.value
    // apply a function to the diff to make the modal move slower
    offset.value = Math.abs(diff) - Math.abs(diff) / 2
    offset.value *= diff > 0 ? 1 : -1
}

function onTouchDrag(e: TouchEvent) {
    onDrag(e.touches[0].clientY)
}

function onClickDrag(e: MouseEvent) {
    if (e.buttons !== 1) return
    onDrag(e.clientY)
}

function onDragEnd() {
    if (offset.value > 50) {
        close()
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
    touchStart.value = undefined
    document.removeEventListener('mousemove', onClickDrag)
}
</script>

<style scoped></style>
