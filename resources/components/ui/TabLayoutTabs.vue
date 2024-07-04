<template>
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
</template>

<script lang="ts" setup>
import { inject, onMounted } from 'vue'
import {
    tabLayoutActiveTabInjection,
    tabLayoutTabsInjection,
} from '@/types/injection'

const tabs = inject(tabLayoutTabsInjection)
const activeTab = inject(tabLayoutActiveTabInjection)

function handleTabClick(template: string) {
    activeTab!.value = template
    // if (props.scrollToPageSection) {
    //     const tabContent = comp.value?.querySelector(`#${template}`)
    //     if (tabContent) {
    //         window.scrollTo({
    //             top: tabContent.offsetTop - header.value?.clientHeight,
    //             behavior: 'smooth',
    //         })
    //     }
    // } else {
    //     activeTab.value = template
    // }
}

const props = defineProps({
    menuWrapperClass: {
        type: String,
        default: 'mx-auto max-w-full overflow-x-auto ',
    },
    menuContainerClass: {
        type: String,
        default: 'z-10 flex w-full flex-nowrap px-4',
    },
    menuBtnSelectedClass: {
        type: String,
        default: 'border-red-600 text-red-600',
    },
    menuBtnClass: {
        type: String,
        default: 'border-neutral-300 text-neutral-500',
    },
})
</script>

<style scoped></style>
