<template>
    <div class="flex flex-col items-center gap-8 w-full">
        <div
            ref="header"
            class="flex flex-col sticky top-0 w-full bg-white"
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
                    @click="activeTab = tab.template"
                >
                    {{ tab.title }}
                </button>
            </div>
        </div>
        <slot :name="activeTab">
            {{ activeTab }}
        </slot>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, PropType, provide, ref, watch } from 'vue'
import { animate, scroll } from 'motion'

const props = defineProps({
    tabs: {
        type: Array as PropType<string[]>,
        required: true,
    },
})

const activeTab = ref(props.tabs[0].template)
const container = ref<HTMLDivElement | undefined>(undefined)
const header = ref<HTMLDivElement | null>(null)
provide('activeTab', activeTab)

watch(
    () => activeTab.value,
    (newValue) => {
        if (container.value) {
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

onMounted(() => {
    scroll(
        animate(header.value, {
            translateY: ['0%', '-150px'],
        }),
        {
            offset: ['start start', '150px'],
        }
    )
})
</script>

<style scoped></style>
