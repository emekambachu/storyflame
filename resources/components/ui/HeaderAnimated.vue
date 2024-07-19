<template>
    <div
        ref="header"
        class="sticky top-0 z-10 flex w-full flex-col bg-white"
    >
        <div class="relative" ref="headerWrapper">
            <div
                v-if="!noAnimation"
                ref="headerContentHidden"
                class="opacity-0"
            >
                <slot name="header" />
            </div>
            <div
                ref="headerContent"
                :class="{
                    'absolute top-0 left-0 right-0': !noAnimation,
                }"
                class="w-full overflow-hidden"
            >
                <slot name="header" />
            </div>
        </div>
        <slot name="sticky" />
    </div>
</template>

<script lang="ts" setup>
import { inject, onMounted, provide, ref, computed } from 'vue'
import { animate, scroll } from 'motion'

const props = defineProps({
    noAnimation: {
        type: Boolean,
        default: false,
    },
})

const header = ref<HTMLDivElement | null>(null)
const headerWrapper = ref<HTMLDivElement | null>(null)
const headerContent = ref<HTMLDivElement | null>(null)
const headerContentHidden = ref<HTMLDivElement | null>(null)

const headerHeight = inject('headerHeight', 0)
const collapseHeaderHeight = inject('collapseHeaderHeight', 0)
const scrollHeight = computed(() => {
    if (headerWrapper.value && headerContent.value) {
        return headerWrapper.value.clientHeight - collapseHeaderHeight
    }
    return 0
})

provide('scrollHeight', scrollHeight)

onMounted(() => {
    if (!props.noAnimation) {
        if (!header.value || !headerContent.value || !headerContentHidden.value || !headerWrapper.value)
            return

        const fullHeight = headerWrapper.value.clientHeight

        scroll(
            animate(header.value, {
                translateY: [
                    '0',
                    `${collapseHeaderHeight - fullHeight}px`,
                ],
                easing: 'linear',
            }),
            {
                offset: ['start start', `${scrollHeight.value}px`],
            }
        )
        scroll(
            animate(headerContent.value, {
                height: [
                    `${fullHeight}px`,
                    `${collapseHeaderHeight}px`,
                ],
                easing: 'linear',
            }),
            {
                offset: ['start start', `${scrollHeight.value}px`],
            }
        )
    }
})
</script>

<style scoped></style>
