<!-- Slideover.vue -->
<template>
    <transition name="slide" @after-leave="$emit('closed')">
        <div v-if="isOpen" class="fixed inset-0 overflow-hidden z-50">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div class="relative w-screen max-w-md">

                        <!--Close slide-over-->
                        <div class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
                            <button @click="closeModal" class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                <span class="sr-only">Close panel</span>
                                <!-- Heroicon name: outline/x -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!--Contents-->
                        <div class="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">

                            <div class="space-y-6 bg-white p-6 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold">Achievement name</h2>
                                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                                </div>
                                <div>
                                    <label for="achievementId" class="block text-sm font-medium text-gray-700">ID</label>
                                    <input id="achievementId" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="achievementName" class="block text-sm font-medium text-gray-700">Achievement name</label>
                                    <input id="achievementName" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                    <input id="color" type="color" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                                    <input id="subtitle" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="publishAt" class="block text-sm font-medium text-gray-700">Publish at</label>
                                    <input id="publishAt" type="date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                    <input id="category" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
                                    <textarea id="purpose" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="extractionDescription" class="block text-sm font-medium text-gray-700">Extraction Description</label>
                                    <textarea id="extractionDescription" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="openEndedQuestions" class="block text-sm font-medium text-gray-700">Example Open-ended Questions</label>
                                    <textarea id="openEndedQuestions" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="dataPoints" class="block text-sm font-medium text-gray-700">Data Points</label>
                                    <input id="dataPoints" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                </div>
                                <button type="button" class="text-blue-600">+ Create new Data Point</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['close', 'closed']);

const closeModal = () => {
    emit('close');
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
</style>
