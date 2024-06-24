<!-- Slideover.vue -->
<template>
    <transition name="slide" @after-leave="$emit('closed')">
        <div v-if="isOpen" class="fixed inset-0 overflow-hidden z-50">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div class="relative w-screen max-w-md overflow-y-auto">

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

                        <form @submit.prevent="submitAchievement">
                            <!--Contents-->
                            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-auto">

                                <div class="flex items-center justify-between bg-stone-300 p-3 sticky top-0">
                                    <h2 class="text-md font-bold">Create new achievement</h2>
                                    <button type="button" class="px-4 py-2 bg-black text-white rounded-full">Save</button>
                                </div>

                                <div class="space-y-6 bg-white p-6 rounded-lg">

                                    <div class="w-full space-x-4 block sm:flex">
                                        <div class="sm:w-2/3 w-full">
                                            <div class="mb-3">
                                                <label
                                                    for="achievementName"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Achievement name
                                                </label>
                                                <input
                                                    v-model="form.name"
                                                    id="achievementName"
                                                    type="text"
                                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150">
                                            </div>
                                            <div>
                                                <label
                                                    for="achievementName"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Color
                                                </label>
                                                <div class="bg-stone-150 border border-gray-300 rounded-md flex">
                                                    <input
                                                        v-model="form.color"
                                                        type="color"
                                                        class="color-picker">
                                                    <p class="my-auto ml-1">{{ form.color }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-3/6 sm:w-1/3 justify-center">
                                            <div class="">
                                                <label
                                                    class="block text-sm font-medium text-gray-700">
                                                    Upload Icon
                                                </label>
                                                <input
                                                    v-if="form.icon === null"
                                                    @change="uploadIcon"
                                                    type="file"
                                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md file-input bg-stone-150"
                                                >
                                                <img v-else
                                                     :src="iconPreview"
                                                     class="w-full h-40 object-cover rounded-md">
                                                <p
                                                    v-if="form.icon !== null"
                                                    @click="form.icon = null"
                                                   class="text-center text-red-500"
                                                   title="delete">x</p>
                                            </div>
                                        </div>

                                    </div>

                                    <div>
                                        <label
                                            for="subtitle"
                                            class="block text-sm font-medium text-gray-700">
                                            Subtitle
                                        </label>
                                        <input
                                            id="subtitle"
                                            type="text"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150">
                                    </div>

                                    <div class="w-full sm:w-1/2">
                                        <label
                                            for="publishAt"
                                            class="block text-sm font-medium text-gray-700">
                                            Publish at
                                        </label>
                                        <input
                                            id="publishAt"
                                            type="date"
                                            :min="new Date().toISOString().split('T')[0]"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150">
                                    </div>

                                    <hr class="my-4">

                                    <div>
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700">
                                            Category
                                        </label>
                                        <div v-if="form.categories?.length > 0">
                                        <span
                                            v-for="(category, index) in form.categories"
                                            :key="index"
                                            class="bg-sky-200 text-sky-600 p-1 rounded-md mr-1"
                                        >
                                            <span class="">
                                                {{ category.name }}
                                                <i class="" @click.prevent="deleteCategory">x</i>
                                            </span>
                                        </span>
                                        </div>
                                        <select
                                            @change.prevent="selectCategory"
                                            id="category"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md bg-stone-150">
                                            <option>Select Category</option>
                                            <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            for="purpose"
                                            class="block text-sm font-medium text-gray-700">
                                            Purpose
                                        </label>
                                        <textarea
                                            v-model="form.purpose"
                                            id="purpose"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                    </div>

                                    <hr class="my-4">

                                    <div>
                                        <label
                                            for="extractionDescription"
                                            class="block text-sm font-medium text-gray-700">
                                            Extraction Description
                                        </label>
                                        <textarea
                                            v-model="form.extraction_description"
                                            id="extractionDescription"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                    </div>
                                    <div>
                                        <label
                                            for="openEndedQuestions"
                                            class="block text-sm font-medium text-gray-700">
                                            Example Open-ended Questions
                                        </label>
                                        <textarea
                                            v-model="form.example"
                                            id="openEndedQuestions"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                                    </div>

                                    <hr class="my-4">

                                    <div>
                                        <label for="dataPoints" class="block text-sm font-medium text-gray-700">Data Points</label>
                                        <input id="dataPoints" type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                    </div>
                                    <button type="button" class="text-blue-600">+ Create new Data Point</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { defineProps, defineEmits, reactive, ref, onMounted, computed } from 'vue'
import store from '@/store/index.js'
import validationService from '@/utils/validation-service.js'

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

const errors = ref({});
const loading = ref(false);

const categories = computed(() => store.state.categories);
const selectCategory = (e) => {
    if(e.target.value !== 'Select Category' && !form.categories.includes(e.target.value)) {
        form.categories.push({
            id: e.target.value,
            name: e.target.options[e.target.selectedIndex].text,
        });
    }

    console.log("CATEGORIES", form.categories);
};

const deleteCategory = (index) => {
    form.categories.splice(index, 1);
};

const submitAchievement = () => {
    console.log("FORM", form);
};

const form = reactive({
    name: '',
    color: '',
    subtitle: '',
    icon: null,
    publish_at: '',
    categories: [],
    data_points: [],
    purpose: '',
    example: '',
    extraction_description: '',
    openEndedQuestions: '',
});

const iconPreview = ref(null);

const uploadIcon = (event) => {
    let validateFileType = validationService.validateFileType(event.target.files[0], ['jpg', 'jpeg', 'png']);
    if(!validateFileType){
        errors.value['icon'] = ['Incorrect file format. allowed: jpg, jpeg, png'];
        form.icon = null;
        return false;
    }

    let validateFileSize = validationService.validateFileSize(event.target.files[0], 1000000);
    if(!validateFileSize){
        errors.value['icon'] = ['File too large, 1mb max'];
        form.icon = null;
        return false;
    }

    //Assign image and path to this variable
    form.icon = event.target.files[0];
    iconPreview.value = URL.createObjectURL(event.target.files[0]);
    errors.value['icon'] = [];
}

onMounted(() => {
    store.dispatch('getData', {
        url: '/api/categories',
        commit_name: 'SET_CATEGORIES',
    });
});

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

.file-input::file-selector-button {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23000000" width="800px" height="800px" viewBox="0 0 24 24" id="image-frame" data-name="Flat Color" class="icon flat-color"><rect id="primary" x="2" y="3" width="20" height="18" rx="2" style="fill: rgb(0, 0, 0);"/><path id="secondary" d="M2.29,17,7,12.29a1,1,0,0,1,1.42,0l2.09,2.1,4.07-4.08a1,1,0,0,1,1.42,0L21.71,16a1,1,0,0,1,.29.71V19a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17.71A1,1,0,0,1,2.29,17ZM7.5,7.5A1.5,1.5,0,1,0,9,6,1.5,1.5,0,0,0,7.5,7.5Z" style="fill: rgb(44, 169, 188);"/></svg>');
    background-size: cover;
    background-position: center;
    width: 100%;
    height: 115px;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    color: transparent;
}

.file-input::-ms-browse {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23000000" width="800px" height="800px" viewBox="0 0 24 24" id="image-frame" data-name="Flat Color" class="icon flat-color"><rect id="primary" x="2" y="3" width="20" height="18" rx="2" style="fill: rgb(0, 0, 0);"/><path id="secondary" d="M2.29,17,7,12.29a1,1,0,0,1,1.42,0l2.09,2.1,4.07-4.08a1,1,0,0,1,1.42,0L21.71,16a1,1,0,0,1,.29.71V19a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17.71A1,1,0,0,1,2.29,17ZM7.5,7.5A1.5,1.5,0,1,0,9,6,1.5,1.5,0,0,0,7.5,7.5Z" style="fill: rgb(44, 169, 188);"/></svg>');
    background-size: cover;
    background-position: center;
    width: 100%;
    height: 115px;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    color: transparent;
}

.color-picker {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    padding: 0;
    overflow: hidden;
}

.color-picker::-webkit-color-swatch-wrapper {
    padding: 0;
}

.color-picker::-webkit-color-swatch {
    border: none;
    border-radius: 50%;
}

.color-picker::-moz-color-swatch {
    border: none;
    border-radius: 50%;
}
</style>
