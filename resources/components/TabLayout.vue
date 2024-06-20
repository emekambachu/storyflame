<template>
    <div
        ref="comp"
        class="flex w-full grow flex-col items-center gap-8"
    >
        <div
            ref="header"
            class="sticky -top-[1px] z-10 flex w-full flex-col bg-white"
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
            <div :class="menuWrapperClass">
                <div
                    ref="container"
                    :class="menuContainerClass"
                >
                    <button
                        v-for="tab in tabs"
                        :key="tab.template"
                        :class="[
                            activeTab == tab.template
                                ? menuBtnSelectedClass
                                : menuBtnClass,
                        ]"
                        :data-key="tab.template"
                        class="select-none whitespace-nowrap border-b py-1 text-sm font-medium [&:not(:first-child)]:pl-5 [&:not(:last-child)]:pr-5"
                        @click="handleTabClick(tab.template)"
                    >
                        {{ tab.title }}
                    </button>
                </div>
            </div>
        </div>

        <template v-if="!scrollToPageSection">
            <div class="w-full grow">
                <slot :name="activeTab">
                    {{ activeTab }}
                </slot>
            </div>
        </template>

        <div
            v-else
            :class="tabsContentClass"
        >
            <div
                v-for="tab in tabs"
                :id="tab.template"
                :key="tab.template"
                :class="tabContentClass"
                class="scroll-section"
            >
                <slot :name="tab.template">
                    {{ tab.template }}
                </slot>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, onUnmounted, PropType, provide, ref, watch } from 'vue'
import { animate, scroll } from 'motion'

const props = defineProps({
    tabs: {
        type: Array as PropType<{ title: string; template: string }[]>,
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

const activeTab = ref(props.tabs[0].template)
const container = ref<HTMLDivElement | undefined>(undefined)
const comp = ref<HTMLDivElement | undefined>(undefined)
const header = ref<HTMLDivElement | null>(null)
const headerContent = ref<HTMLDivElement | null>(null)
const headerContentHidden = ref<HTMLDivElement | null>(null)
provide('activeTab', activeTab)

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
                offset: ['start start', `${+props.headerHeight / 2}px`],
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
                offset: ['start start', `${+props.headerHeight / 2}px`],
            }
        )
    }
})
</script>

<style scoped></style>
