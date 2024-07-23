<template>
    <div class="w-full overflow-x-auto bg-white px-3 py-4">
        <div class="flex gap-2">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                :class="[
                    'rounded-lg px-3 py-2 text-base',
                    modelValue === tab.key
                        ? 'bg-stone-800 text-stone-50'
                        : 'bg-stone-100 text-stone-500',
                ]"
                @click="setActiveTab(tab.key)"
            >
                {{ tab.title }}
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits } from 'vue'

interface Tab {
    title: string
    key: string
}

const props = defineProps<{
    tabs: Tab[]
    modelValue: string
}>()

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const setActiveTab = (tabKey: string) => {
    emit('update:modelValue', tabKey)
}
</script>
