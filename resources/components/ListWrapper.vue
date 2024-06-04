<template>
    <div class="px-4 pb-10 flex flex-col">
        <div class="flex flex-col mt-6">
            <div class="w-full flex items-center justify-between">
                <h1 class="text-lg text-black font-bold">{{ title }}</h1>
                <div class="flex items-center gap-3">
                    <button @click="showSearch = !showSearch">
                        <search-icon
                            :class="
                                showSearch ? 'text-orange-600' : 'text-zinc-800'
                            "
                        />
                    </button>
                    <button
                        v-if="enableFilter"
                        @click="showModal = true"
                    >
                        <filter-icon />
                    </button>
                </div>
            </div>
            <div
                v-if="showSearch"
                class="relative mt-4"
            >
                <input
                    class="w-full pl-3 pr-10 py-2.5 bg-slate-100 rounded-lg min-h-10 text-slate-400 text-lg outline-0"
                    :placeholder="placeholder"
                    :value="searchValue"
                    @input="
                        ($event) => {
                            emit('update:searchValue', $event?.target?.value)
                        }
                    "
                />
                <button class="absolute top-3 right-3 pt-1">
                    <search-icon
                        v-if="!searchValue?.length"
                        class="text-gray-500 h-4"
                    />
                    <x-mark-icon
                        v-else
                        class="text-gray-500 h-4"
                    />
                </button>
            </div>

            <slot name="content" />
        </div>
    </div>

    <modal-bottom
        v-if="showModal"
        @close="showModal = false"
        class="p-2"
        container-class="rounded-3xl bg-pure-white px-6 pb-6 relative w-full flex flex-col gap-6"
    >
        <template #content>
            <slot name="modal" />
        </template>
        <template #closeButton1>
            <slot name="closeButton1" />
        </template>
        <template #closeButton2>
            <slot name="closeButton2" />
        </template>
    </modal-bottom>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ModalBottom from '@/components/ModalBottom.vue'

import XMarkIcon from '@/components/icons/XmarkIcon.vue'
import SearchIcon from '@/components/icons/SearchIcon.vue'
import FilterIcon from '@/components/icons/FilterIcon.vue'

const emit = defineEmits(['update:searchValue'])

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    enableFilter: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: 'Search',
    },
    searchValue: {
        type: String,
        required: true,
    },
})

const showSearch = ref(false)

const showModal = ref(false)
</script>

<style scoped></style>
