<template>
    <div class="fixed bottom-0 right-0 text-white">
        <div
            v-if="isOpen"
            class="fixed bottom-8 right-1 bg-red-500 text-center"
        >
            <h6>Mock API</h6>
            <ul class="w-full divide-y">
                <li
                    v-if="groups.length === 0"
                    class="text-sm"
                >
                    No mocks available
                </li>
                <li
                    v-for="(group, i) in groups"
                    :key="i"
                    class="w-full text-sm"
                >
                    {{ group.name }}
                    <ul class="w-full">
                        <li
                            v-for="(mock, key) in group.mocks"
                            :key="key"
                            class="w-full text-xs"
                        >
                            <button
                                class="px-2"
                                :class="{
                                    'bg-green-600': enabledMocks.includes(key),
                                    'bg-red-500': !enabledMocks.includes(key),
                                }"
                                @click="toggleMock(key)"
                            >
                                {{ key }}
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <button
            class="bg-red-500 opacity-50"
            @click="isOpen = !isOpen"
        >
            mck
        </button>
    </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue'
import { useMockStore } from '@/stores/mocks.js'

const { groups, toggleMock, enabledMocks } = useMockStore()
const isOpen = ref(false)
</script>
