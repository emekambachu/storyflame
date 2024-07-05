<template>
    <div
        ref="container"
        class="w-full grow"
    >
        <template v-if="!oneLine">
            <slot :name="activeTab">
                {{ activeTab }}
            </slot>
        </template>
        <template v-else>
            <section
                v-for="tab in tabs"
                :key="tab.template"
                :data-key="tab.template"
            >
                <slot :name="tab.template">
                    {{ tab.template }}
                </slot>
            </section>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { inject, onMounted, onUnmounted, ref, watch } from 'vue'
import {
    tabLayoutActiveTabInjection,
    tabLayoutTabsInjection,
} from '@/types/injection'

const props = defineProps({
    oneLine: {
        type: Boolean,
        default: false,
    },
})

const container = ref<HTMLElement | null>(null)

const tabs = inject(tabLayoutTabsInjection)
const activeTab = inject(tabLayoutActiveTabInjection)
const containerY = ref(0)
const currentTab = ref(activeTab!.value)
const disableOnScroll = ref(false)
const disableScrollTo = ref(false)

watch(
    () => activeTab!.value,
    () => {
        if (disableScrollTo.value) return
        if (props.oneLine) {
            const section = container.value?.querySelector(
                `[data-key="${activeTab!.value}"]`
            ) as HTMLElement
            if (section) {
                disableOnScroll.value = true
                window.scrollTo({
                    top: section.offsetTop - containerY.value,
                    behavior: 'smooth',
                })
            }
        }
    }
)

function onScroll(event: Event) {
    const sections = container.value?.children
    if (!sections) return
    for (let i = sections.length - 1; i >= 0; i--) {
        const section = sections[i] as HTMLElement
        const { top } = section.getBoundingClientRect()
        if (top <= containerY.value) {
            // activeTab!.value = section.dataset.key!
            currentTab.value = section.dataset.key!
            disableScrollTo.value = true
            if (disableOnScroll.value) {
                if (currentTab.value === activeTab!.value) {
                    disableOnScroll.value = false
                }
            } else {
                activeTab!.value = currentTab.value
            }
            requestAnimationFrame(() => {
                disableScrollTo.value = false
            })

            break
        }
    }
}

onMounted(() => {
    if (props.oneLine) {
        containerY.value = container.value!.getBoundingClientRect().top
        document.addEventListener('scroll', onScroll)
    }
})

onUnmounted(() => {
    if (props.oneLine) {
        document.removeEventListener('scroll', onScroll)
    }
})
</script>

<style scoped></style>
