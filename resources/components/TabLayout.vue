<template>
    <div class="flex flex-col items-center gap-8 w-full">
        <div
            ref="header"
            class="flex flex-col sticky -top-[1px] w-full bg-white z-10"
        >
            <div class="overflow-hidden">
                <slot />
            </div>
            <div
                ref="container"
                class="flex flex-nowrap w-full max-w-full mx-auto overflow-x-scroll px-4"
            >
                <button
                    v-for="tab in tabs"
                    :key="tab.template"
                    :class="[
                        activeTab == tab.template
                            ? 'text-red-600 border-red-600'
                            : 'text-neutral-500 border-neutral-300',
                    ]"
                    :data-key="tab.template"
                    class="text-sm font-medium [&:not(:last-child)]:pr-5 [&:not(:first-child)]:pl-5 whitespace-nowrap select-none py-1 border-b"
                    @click="handleTabClick(tab.template)"
                >
                    {{ tab.title }}
                </button>
            </div>
        </div>

        <template v-if="!scrollToPageSection">
            <slot :name="activeTab">
                {{ activeTab }}
            </slot>
        </template>

        <div
            v-else
            :class="tabsContentClass"
        >
            <div
                v-for="tab in tabs"
                :key="tab.template"
                :id="tab.template"
                :class="tabContentClass"
            >
                <slot :name="tab.template">
                    {{ tab.template }}
                </slot>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, PropType, provide, ref, watch } from 'vue'
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
    tabsContentClass: {
        type: String,
        default: 'flex flex-col gap-8 w-full',
    },
    tabContentClass: {
        type: String,
        default: '',
    },
    animateTranslateY: {
        type: String,
        default: '150px',
    }
})

const activeTab = ref(props.tabs[0].template)
const container = ref<HTMLDivElement | undefined>(undefined)
const header = ref<HTMLDivElement | null>(null)
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
        const tabContent = document.getElementById(template)
        if (tabContent) {
            tabContent.scrollIntoView({ behavior: 'smooth' })
        }
    } else {
        activeTab.value = template
    }
}

onMounted(() => {
    scroll(
        animate(header.value, {
            translateY: ['0%', '-' + props.animateTranslateY],
        }),
        {
            offset: ['start start', '150px'],
        }
    )
    if (header.value) {
        header.value.style.position = 'sticky'
        header.value.style.top = '0'
        header.value.style.zIndex = '10'
    }
})
</script>

<style scoped></style>
