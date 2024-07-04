<template>
    <div
        ref="comp"
        class="flex w-full grow flex-col items-center"
        :style="{
        minHeight: `calc(100dvh + ${headerHeight - collapseHeaderHeight}px)`,
        }"
    >
        <slot />
    </div>
</template>

<script lang="ts" setup>
import { onMounted, onUnmounted, PropType, provide, ref, watch } from 'vue'
import { Tab } from '@/types/layout'
import {
    tabLayoutActiveTabInjection,
    tabLayoutTabsInjection,
} from '@/types/injection'

const props = defineProps({
    tabs: {
        type: Array as PropType<Tab[]>,
        required: true,
    },
    scrollToPageSection: {
        type: Boolean,
        default: false,
    },
    menuBtnClass: {
        type: String,
        default: 'border-neutral-300 text-neutral-500',
    },
    menuBtnSelectedClass: {
        type: String,
        default: 'border-red-600 text-red-600',
    },
    menuContainerClass: {
        type: String,
        default: 'z-10 flex w-full flex-nowrap px-4 ',
    },
    menuWrapperClass: {
        type: String,
        default: 'mx-auto max-w-full overflow-x-auto',
    },
    tabsContentClass: {
        type: String,
        default: 'flex flex-col gap-8 w-full',
    },
    tabContentClass: {
        type: String,
        default: '',
    },
    tabsOnePage: {
        type: Boolean,
        default: false,
    },
    headerHeight: {
        type: [Number, String],
        default: 400,
    },
    collapseHeaderHeight: {
        type: [Number, String],
        default: 100,
    },
})

const activeTab = ref(props.tabs[0].template)
const container = ref<HTMLDivElement | undefined>(undefined)
const comp = ref<HTMLDivElement | undefined>(undefined)

provide(tabLayoutTabsInjection, props.tabs)
provide(tabLayoutActiveTabInjection, activeTab)
provide('headerHeight', props.headerHeight)
provide('collapseHeaderHeight', props.collapseHeaderHeight)

watch(
    () => activeTab.value,
    (newValue) => {
        if (!props.scrollToPageSection && container.value) {
            const tab = container.value.querySelector(
                `[data-key="${newValue}"]`
            ) as HTMLButtonElement
            if (tab) {
                tab.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center',
                })
            }
        }
    }
)

function handleTabClick(template: string) {
    if (props.scrollToPageSection) {
        const tabContent = comp.value?.querySelector(`#${template}`)
        if (tabContent) {
            window.scrollTo({
                top: tabContent.offsetTop - header.value?.clientHeight,
                behavior: 'smooth',
            })
        }
    } else {
        activeTab.value = template
    }
}

function onScroll(event: Event) {
    const sections = comp.value?.querySelectorAll('.scroll-section')
    if (!sections) return
    const activeSection = Array.from(sections).find((section) => {
        const sectionTop =
            section.getBoundingClientRect().top - header.value?.clientHeight
        return sectionTop <= 0 && sectionTop + section.clientHeight > 0
    }) as HTMLElement
    if (activeSection) {
        activeTab.value = activeSection.id
    }
}

onUnmounted(() => {
    if (props.scrollToPageSection) {
        document.removeEventListener('scroll', onScroll)
    }
})

onMounted(() => {
    if (props.scrollToPageSection) {
        document.addEventListener('scroll', onScroll)
    }
})
</script>

<style scoped></style>
