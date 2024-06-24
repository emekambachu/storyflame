<template>
    <div class="mx-auto max-w-full overflow-x-auto">
        <div
            ref="container"
            class="z-10 flex w-full flex-nowrap px-4"
        >
            <button
                v-for="tab in tabs"
                :key="tab.template"
                :class="[
                    activeTab === tab.template
                        ? 'border-red-600 text-red-600'
                        : 'border-neutral-300 text-neutral-500',
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
</script>

<style scoped></style>
