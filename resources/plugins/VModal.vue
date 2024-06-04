<template>
    <transition
        :class="{
            'bg-black/50': backdrop,
        }"
        :name="backdrop ? 'backdrop' : 'none'"
        class="transform-gpu"
        @enter="componentOpen = true"
        @after-leave="emit('close')"
    >
        <div
            v-if="backdropOpen"
            class="fixed inset-0 flex items-end overscroll-contain z-50"
            @click.self="onBackdropClick"
        >
            <transition
                :name="props.type"
                class="transform-gpu"
                @leave="backdropOpen = false"
            >
                <component
                    :is="component"
                    v-if="componentOpen"
                    :class="{
                        'scale-90 -translate-y-8': order > 1,
                        'h-full w-full': type === 'full',
                    }"
                    v-bind="$attrs"
                    @close="close"
                />
            </transition>
        </div>
    </transition>
</template>

<script lang="ts" setup>
import { onMounted, provide, ref, watch } from 'vue'

const emit = defineEmits(['close', 'closing'])

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
    component: {
        type: Object,
        required: true,
    },
    order: {
        type: Number,
        default: 0,
    },
    type: {
        type: String,
        default: 'popup',
    },
    backdrop: {
        type: Boolean,
        default: true,
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

function onBackdropClick() {
    if (props.backdrop) close()
}

watch(
    () => props.open,
    (open) => {
        if (!open) {
            close()
        }
    },
    { immediate: true }
)
</script>

<style scoped>
.backdrop-enter-active,
.backdrop-leave-active {
    transition: opacity 0.3s;
}

.backdrop-enter-from,
.backdrop-leave-to {
    opacity: 0;
}

.backdrop-enter-to,
.backdrop-leave-from {
    opacity: 1;
}

.none-enter-active,
.none-leave-active {
    transition: 0.3s;
}

.popup-enter-from,
.popup-leave-to {
    transform: translateY(100%);
}

.popup-enter-to,
.popup-leave-from {
    transform: translateY(0);
}

.popup-enter-active,
.popup-leave-active {
    transition: transform 0.3s;
}

.full-enter-from,
.full-leave-to {
    transform: scale(0.9);
    opacity: 0;
}

.full-enter-to,
.full-leave-from {
    transform: scale(1);
    opacity: 1;
}

.full-enter-active,
.full-leave-active {
    transition:
        transform 0.3s,
        opacity 0.3s;
}
</style>
