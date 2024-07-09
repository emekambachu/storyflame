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
                    mainBtnClass,
                ]"
                :data-key="tab.template"
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
        default: 'mx-auto w-full max-w-full overflow-x-auto px-2',
    },
    menuContainerClass: {
        type: String,
        default: 'z-10 flex w-full flex-nowrap pt-5',
    },
    mainBtnClass: {
        type: String,
        default:
            'select-none whitespace-nowrap px-4 pb-2 text-base font-medium',
    },
    menuBtnSelectedClass: {
        type: String,
        default: 'border-b border-stone-950 text-stone-950',
    },
    menuBtnClass: {
        type: String,
        default: 'border-b border-stone-200 text-stone-400',
    },
})
</script>

<style scoped></style>
