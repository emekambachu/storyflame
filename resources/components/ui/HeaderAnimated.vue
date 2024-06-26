<template>
    <div
        ref="header"
        class="sticky top-0 z-10 flex w-full flex-col bg-white"
    >
        <div class="relative">
            <div
                v-if="!noAnimation"
                ref="headerContentHidden"
                class="opacity-0"
            ></div>
            <div
                ref="headerContent"
                :class="{
                    'absolute bottom-0': !noAnimation,
                }"
                class="h-full w-full overflow-hidden"
            >
                <slot />
            </div>
        </div>
        <slot name="sticky" />
    </div>
</template>

<script lang="ts" setup>
import { onMounted, provide, ref } from 'vue'
import { animate, scroll } from 'motion'

const props = defineProps({
    headerHeight: {
        type: [Number, String],
        default: 400,
    },
    collapseHeaderHeight: {
        type: [Number, String],
        default: 100,
    },
    noAnimation: {
        type: Boolean,
        default: false,
    },
})

const header = ref<HTMLDivElement | null>(null)
const headerContent = ref<HTMLDivElement | null>(null)
const headerContentHidden = ref<HTMLDivElement | null>(null)

const scrollHeight = +props.headerHeight - +props.collapseHeaderHeight
provide('scrollHeight', scrollHeight)

onMounted(() => {
    if (!props.noAnimation) {
        if (!header.value || !headerContent.value || !headerContentHidden.value)
            return
        headerContentHidden.value?.style.setProperty(
            'height',
            `${props.headerHeight}px`
        )

        const outerHeight = header.value?.getBoundingClientRect()
        console.log(outerHeight)

        scroll(
            animate(header.value, {
                translateY: [
                    '0',
                    `${+props.collapseHeaderHeight - +props.headerHeight}px`,
                ],
                easing: 'linear',
            }),
            {
                // offset: ['start start', `${+props.headerHeight / 2}px`],
                offset: ['start start', `${scrollHeight}px`],
            }
        )
        scroll(
            animate(headerContent.value, {
                height: [
                    `${props.headerHeight}px`,
                    `${props.collapseHeaderHeight}px`,
                ],
                easing: 'linear',
            }),
            {
                offset: ['start start', `${scrollHeight}px`],
            }
        )
    }
})
</script>

<style scoped></style>
