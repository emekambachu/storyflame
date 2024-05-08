<template>
    <transition
        class="bg-black/50 transform-gpu"
        enter-active-class="transition ease-in-out duration-300"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out duration-300"
        leave-to-class="opacity-0"
        @enter="componentOpen = true"
        @after-leave="emit('close')"
    >
        <div
            v-if="backdropOpen"
            class="fixed inset-0 flex items-end overscroll-contain z-50"
            @click.self="close"
        >
            <transition
                class="transform-gpu transition duration-300"
                enter-active-class="ease-in-out"
                enter-from-class="transform translate-y-full"
                enter-to-class="transform translate-y-0"
                leave-active-class="ease-in-out"
                leave-from-class="transform translate-y-0"
                leave-to-class="transform translate-y-full"
                @leave="backdropOpen = false"
            >
                <component
                    :is="component"
                    v-if="componentOpen"
                    :class="{
                        'scale-90 -translate-y-8': order > 1,
                    }"
                    v-bind="attrs"
                    @close="close"
                />
            </transition>
        </div>
    </transition>
</template>

<script lang="ts" setup>
import { onMounted, provide, ref } from 'vue'

const emit = defineEmits(['close', 'closing'])

defineProps({
    component: {
        type: Object,
        required: true,
    },
    attrs: {
        type: Object,
        default: () => ({}),
    },
    order: {
        type: Number,
        default: 0,
    },
})

provide('close-current-modal', close)

const backdropOpen = ref(false)
const componentOpen = ref(false)

onMounted(() => {
    backdropOpen.value = true
})

function close() {
    emit('closing')
    componentOpen.value = false
}
</script>

<style scoped></style>
